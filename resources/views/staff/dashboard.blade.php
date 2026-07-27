@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển Nhân Viên')

@section('header')
    <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
        Bảng Điều Khiển Nhân Viên
    </h2>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Thẻ Check-in -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 text-center relative overflow-hidden group">
        <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center text-primary text-2xl mb-4 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-user-clock"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chấm Công Hôm Nay</h3>
        <p class="text-sm text-slate-500 mb-6">Trạng thái: 
            @php
                $todayAtt = \App\Models\Attendance::where('user_id', auth()->id())->whereDate('date', today())->first();
            @endphp
            @if($todayAtt)
                <span class="text-emerald-500 font-bold">Đã điểm danh ({{ \Carbon\Carbon::parse($todayAtt->check_in)->format('H:i') }})</span>
            @else
                <span class="text-amber-500 font-bold">Chưa điểm danh</span>
            @endif
        </p>
        
        @if(!$todayAtt)
        <form action="{{ route('admin.staff.attendance') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="hidden" name="status" value="present">
            <button type="submit" class="bg-primary hover:bg-blue-600 text-white font-bold px-6 py-2 rounded-xl shadow-md transition-colors inline-block w-full">
                Điểm Danh Ngay
            </button>
        </form>
        @else
            <button disabled class="bg-slate-100 dark:bg-slate-700 text-slate-400 font-bold px-6 py-2 rounded-xl w-full cursor-not-allowed">
                Đã Điểm Danh
            </button>
        @endif
    </div>

    <!-- Stats -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Đơn Đặt Sân Hôm Nay</h3>
        @php
            $todayBookings = \App\Models\Booking::whereDate('booking_date', today())->count();
        @endphp
        <p class="text-4xl font-heading font-black text-slate-800 dark:text-white">{{ $todayBookings }}</p>
        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-colors"></div>
    </div>
</div>

<!-- Lịch Đặt Hôm Nay -->
<div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg">Lịch Đặt Sân Hôm Nay ({{ today()->format('d/m/Y') }})</h3>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-primary hover:underline font-medium">Xem tất cả lịch</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                <tr>
                    <th class="px-4 py-3 rounded-l-lg">Khách Hàng</th>
                    <th class="px-4 py-3">Sân / Khung Giờ</th>
                    <th class="px-4 py-3">Mã Đơn</th>
                    <th class="px-4 py-3 rounded-r-lg">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @php
                    $bookings = \App\Models\Booking::with(['user', 'details.timeSlot', 'details.field'])
                                ->whereDate('booking_date', today())
                                ->orderBy('id', 'desc')
                                ->get();
                @endphp
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="px-4 py-4 font-bold text-slate-900 dark:text-white">
                            {{ $booking->user->name ?? 'Khách vãng lai' }}
                            <div class="text-xs font-normal text-slate-500">{{ $booking->user->phone ?? '' }}</div>
                        </td>
                        <td class="px-4 py-4">
                            @foreach($booking->details as $detail)
                                <div class="font-bold text-primary">{{ $detail->field->name ?? 'Sân' }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($detail->timeSlot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->timeSlot->end_time)->format('H:i') }}</div>
                            @endforeach
                        </td>
                        <td class="px-4 py-4 font-mono text-slate-500">{{ $booking->booking_code }}</td>
                        <td class="px-4 py-4">
                            @if($booking->status == 'pending')
                                <span class="bg-amber-100 text-amber-600 px-2 py-1 rounded-md text-xs font-bold block mb-2 w-max">Chờ duyệt</span>
                                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-2 py-1 rounded font-medium transition-colors">Xác nhận</button>
                                </form>
                            @elseif($booking->status == 'confirmed')
                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-bold block mb-2 w-max">Đã xác nhận</span>
                                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="text-xs bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-2 py-1 rounded font-medium transition-colors">Hoàn thành (Tích điểm)</button>
                                </form>
                            @elseif($booking->status == 'completed')
                                <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md text-xs font-bold">Hoàn thành</span>
                            @else
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-xs font-bold">Đã hủy</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                            Hôm nay chưa có lịch đặt sân nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
