@extends('layouts.admin')

@section('title', 'Đơn Đặt Sân #' . $booking->id . ' - PitchManage')
@section('page_title', 'Chi Tiết Đơn Đặt Sân #' . $booking->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        
        <!-- Booked Fields / Slots -->
        <div class="card border mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-play text-success me-2"></i>Lịch Đá Đã Đăng Ký</h5>
                <span class="text-secondary small">Ngày đá: <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong></span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Sân</th>
                                <th>Loại Sân</th>
                                <th>Khung Giờ</th>
                                <th>Đơn Giá Chi Tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->bookingDetails as $detail)
                                <tr>
                                    <td class="fw-bold">{{ $detail->footballField->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $detail->footballField->fieldType->name }}</span></td>
                                    <td><span class="badge bg-success"><i class="fa-regular fa-clock me-1"></i> {{ $detail->timeSlot->name }}</span></td>
                                    <td class="fw-bold text-success">{{ number_format($detail->price) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Services & Ordered Services -->
        <div class="card border mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-cubes text-success me-2"></i>Dịch Vụ Sử Dụng Thêm</h5>
                
                <!-- Add Service Form Trigger (Active only if not completed/cancelled) -->
                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Dịch Vụ
                    </button>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Dịch Vụ</th>
                                <th>Đơn Giá</th>
                                <th>Số Lượng</th>
                                <th>Thành Tiền</th>
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <th>Hành Động</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking->serviceOrders as $so)
                                <tr>
                                    <td class="text-start ps-4 fw-semibold">{{ $so->service->name }}</td>
                                    <td>{{ number_format($so->price) }}đ / {{ $so->service->unit }}</td>
                                    <td>{{ $so->quantity }} {{ $so->service->unit }}</td>
                                    <td class="fw-bold text-success">{{ number_format($so->total_amount) }}đ</td>
                                    @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                        <td>
                                            <form action="{{ route('admin.bookings.remove-service', [$booking->id, $so->id]) }}" method="POST" onsubmit="return confirm('Xóa dịch vụ này khỏi lịch đặt sân?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ ($booking->status === 'pending' || $booking->status === 'confirmed') ? 5 : 4 }}" class="text-center py-4 text-secondary">
                                        Chưa có dịch vụ đi kèm nào được chọn.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking details summary panel -->
    <div class="col-lg-4">
        <!-- Quick stats card -->
        <div class="card border mb-4">
            <div class="card-header py-3 bg-dark text-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-info text-success me-2"></i>Chi Tiết Đơn Đặt</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3 text-center">
                    <small class="text-secondary d-block">Trạng thái hiện tại:</small>
                    @if($booking->status === 'pending')
                        <span class="badge bg-warning text-dark fs-6 py-2 px-3 mt-1 w-100"><i class="fa-solid fa-spinner fa-spin me-1"></i> Chờ duyệt xác nhận</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge bg-primary fs-6 py-2 px-3 mt-1 w-100"><i class="fa-solid fa-circle-check me-1"></i> Đã duyệt xác nhận</span>
                    @elseif($booking->status === 'completed')
                        <span class="badge bg-success fs-6 py-2 px-3 mt-1 w-100"><i class="fa-solid fa-circle-check me-1"></i> Đã hoàn thành</span>
                    @else
                        <span class="badge bg-danger fs-6 py-2 px-3 mt-1 w-100"><i class="fa-solid fa-circle-xmark me-1"></i> Đã hủy bỏ</span>
                    @endif
                </div>

                <hr class="my-3 opacity-25">

                <div class="mb-2">
                    <small class="text-secondary">Tên khách hàng:</small>
                    <div class="fw-bold">{{ $booking->customer_name }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-secondary">Số điện thoại:</small>
                    <div class="fw-bold">{{ $booking->customer_phone }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-secondary">Khách đăng ký qua hệ thống:</small>
                    <div class="fw-bold text-success">{{ $booking->user ? $booking->user->name : 'Vãng lai (Đặt tay)' }}</div>
                </div>

                @if($booking->notes)
                    <div class="mb-3">
                        <small class="text-secondary">Ghi chú của khách:</small>
                        <p class="mb-0 bg-light p-2.5 rounded border small italic">{{ $booking->notes }}</p>
                    </div>
                @endif

                <hr class="my-3 opacity-25">

                <!-- Totals -->
                <div class="bg-light p-3 rounded border border-success-subtle mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary">Tổng cộng tiền:</span>
                        <span class="fw-bold text-success fs-5">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                    @if($booking->invoice)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary">Khấu trừ giảm giá:</span>
                            <span class="fw-semibold text-danger">-{{ number_format($booking->invoice->discount) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="fw-bold text-success fs-5">Thực thu thanh toán:</span>
                            <span class="fw-bold text-success fs-4">{{ number_format($booking->invoice->final_amount) }}đ</span>
                        </div>
                    @endif
                </div>

                <!-- Admin Action Triggers -->
                @if($booking->status === 'pending')
                    <div class="d-flex flex-column gap-2">
                        <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold"><i class="fa-solid fa-check me-1"></i> Phê Duyệt Lịch Đặt</button>
                        </form>
                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch này?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 py-2"><i class="fa-solid fa-xmark me-1"></i> Hủy Đơn Đặt Sân</button>
                        </form>
                    </div>
                @endif

                @if($booking->status === 'confirmed')
                    <!-- Checkout trigger: opens the checkout panel -->
                    <button class="btn btn-success w-100 py-2.5 fw-bold" data-bs-toggle="collapse" data-bs-target="#checkoutCollapse">
                        <i class="fa-solid fa-cash-register me-1"></i> Thanh Toán & Xuất Hóa Đơn
                    </button>

                    <!-- Checkout Form Collapse -->
                    <div class="collapse mt-3" id="checkoutCollapse">
                        <div class="p-3 border rounded bg-light">
                            <form action="{{ route('admin.bookings.checkout', $booking->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label fw-semibold small">Phương thức thanh toán</label>
                                    <select name="payment_method" id="payment_method" class="form-select form-select-sm" required>
                                        <option value="cash">Tiền mặt (Cash)</option>
                                        <option value="bank_transfer">Chuyển khoản (Bank Transfer)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="form-label fw-semibold small">Khấu trừ giảm giá (VND)</label>
                                    <input type="number" name="discount" id="discount" class="form-control form-control-sm" placeholder="Nhập tiền giảm giá (nếu có)" min="0" value="0">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold"><i class="fa-solid fa-file-invoice-dollar me-1"></i> Xác Nhận Thanh Toán</button>
                            </form>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch này?');" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 py-2"><i class="fa-solid fa-xmark me-1"></i> Hủy Lịch</button>
                    </form>
                @endif

                @if($booking->status === 'completed' && $booking->invoice)
                    <a href="{{ route('admin.invoices.show', $booking->invoice->id) }}" class="btn btn-outline-success w-100 py-2 fw-semibold">
                        <i class="fa-solid fa-print me-1"></i> Xem & In Hóa Đơn PDF
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
@if($booking->status === 'pending' || $booking->status === 'confirmed')
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addServiceModalLabel"><i class="fa-solid fa-cart-plus text-success me-1"></i> Thêm Dịch Vụ Đi Kèm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bookings.add-services', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="service_id" class="form-label fw-semibold">Chọn Dịch Vụ</label>
                        <select name="service_id" id="service_id" class="form-select" required>
                            <option value="" disabled selected>-- Chọn dịch vụ --</option>
                            @foreach($services as $svc)
                                <option value="{{ $svc->id }}">
                                    {{ $svc->name }} ({{ number_format($svc->price) }}đ / {{ $svc->unit }}, Kho: {{ $svc->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">Số Lượng</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm Vào Đơn</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
