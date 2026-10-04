<div class="bg-[#EAEDED] min-h-screen font-['Amazon Ember', 'Helvetica Neue', Arial, sans-serif]">
    <div class="container mx-auto px-3 sm:px-4 max-w-4xl py-6 sm:py-12">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

            <!-- Success Header -->
            <div class="bg-green-50 p-4 sm:p-8 border-b border-green-200">
                <div class="flex items-center justify-center">
                    <div class="w-14 h-14 sm:w-20 sm:h-20 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-green-600 mt-3 sm:mt-4">Payment Successful!</h1>
                <p class="text-sm sm:text-base text-center text-gray-600 mt-1 sm:mt-2 px-2">{{ $message }}</p>
            </div>

            <!-- Order Details -->
            <div class="p-4 sm:p-6 md:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Repair Request Details</h2>

                <!-- Grid - Mobile First -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Left Column -->
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Request ID</p>
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">#{{ $repairRequest->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Device</p>
                            <p class="font-semibold text-gray-900 text-sm sm:text-base break-words">
                                {{ $repairRequest->category->name }}
                                {{ $repairRequest->brand->name }}
                                {{ $repairRequest->model->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Customer</p>
                            <p class="font-semibold text-gray-900 text-sm sm:text-base break-words">{{ $repairRequest->full_name }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Payment Method</p>
                            <p class="font-semibold text-gray-900 text-sm sm:text-base capitalize">{{ $repairRequest->payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Total Amount</p>
                            <p class="font-semibold text-green-600 text-lg sm:text-xl">${{ number_format($repairRequest->total_price, 2) }}</p>
                            <p class="text-[10px] sm:text-xs text-gray-500">(Includes 8% sales tax)</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800">
                                Confirmed
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Problems List with Tax Breakdown -->
                <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Selected Problems</h3>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        @php
                            $problems = is_string($repairRequest->problems)
                                ? json_decode($repairRequest->problems, true)
                                : $repairRequest->problems;
                            $subtotal = $repairRequest->subtotal ?? 0;
                            $tax = $repairRequest->tax ?? 0;
                            $total = $repairRequest->total_price ?? 0;
                        @endphp

                        <ul class="divide-y divide-gray-200">
                            @if($problems && is_array($problems))
                                @foreach($problems as $problem)
                                <li class="py-2 flex justify-between items-center">
                                    <span class="text-gray-700 text-sm sm:text-base break-words pr-2">{{ $problem['name'] ?? 'Unknown Problem' }}</span>
                                    <span class="font-medium text-gray-900 text-sm sm:text-base whitespace-nowrap">${{ number_format($problem['price'] ?? 0, 2) }}</span>
                                </li>
                                @endforeach
                            @else
                                <li class="py-2 text-gray-500 text-center text-sm">No problems listed</li>
                            @endif
                        </ul>

                        <!-- Subtotal -->
                        <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-gray-200">
                            <div class="flex justify-between text-xs sm:text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <!-- Tax -->
                        <div class="flex justify-between text-xs sm:text-sm mt-1">
                            <span class="text-gray-600">Tax (8%)</span>
                            <span class="font-medium text-gray-900">${{ number_format($tax, 2) }}</span>
                        </div>

                        <!-- Total -->
                        <div class="mt-2 sm:mt-3 pt-2 sm:pt-3 border-t-2 border-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900 text-sm sm:text-base">Total Amount</span>
                                <span class="font-bold text-green-600 text-base sm:text-lg">${{ number_format($total, 2) }}</span>
                            </div>
                            <p class="text-[10px] sm:text-xs text-gray-500 text-right mt-0.5 sm:mt-1">Includes 8% sales tax</p>
                        </div>
                    </div>
                </div>

                <!-- Next Steps - Mobile Optimized -->
                <div class="mt-4 sm:mt-8 p-3 sm:p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="font-semibold text-blue-900 mb-2 text-sm sm:text-base">What's Next?</h3>
                    <ul class="text-xs sm:text-sm text-blue-800 space-y-2 sm:space-y-2">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>We will contact you within 24 hours to confirm your repair appointment.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>A confirmation email has been sent to <strong class="break-all">{{ $repairRequest->email }}</strong>.</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Please keep your request ID <strong>#{{ $repairRequest->id }}</strong> for reference.</span>
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons - Mobile Optimized -->
                <div class="mt-4 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <a href="{{ route('device-repair', ['slug' => $repairRequest->category->slug]) }}"
                       class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-[#5C9F01] text-white rounded-lg hover:bg-[#4a7e01] transition-colors font-medium text-sm sm:text-base w-full sm:w-auto">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Book Another Repair
                    </a>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm sm:text-base w-full sm:w-auto">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
