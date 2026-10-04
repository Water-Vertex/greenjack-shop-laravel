<div>
    <div class="container mx-auto px-4 max-w-7xl py-8">
        <!-- Breadcrumb -->
        <div class="mb-6 no-print">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-[#5B9F01] transition">Home</a>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <a href="{{ route('customer.account') }}" wire:navigate class="hover:text-[#5B9F01] transition">My Account</a>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <span class="text-gray-800 font-medium">Order Details</span>
            </nav>
        </div>

        @if($order)
        <!-- Invoice Content - This will be printed -->
        <div id="invoice-content">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Invoice Header -->
                <div class="bg-[#00349B] px-6 py-4 print:bg-[#00349A]">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-10 print:block hidden">
                                <h1 class="text-xl font-bold text-white">INVOICE</h1>
                            </div>
                            <p class="text-white/80 text-sm">Order #{{ $order->order_number }}</p>
                            <p class="text-white/60 text-xs">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3 no-print">
                            <span class="px-4 py-2 rounded-lg text-sm font-semibold {{ $order->status_badge_color ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <a href="{{ route('customer.account') }}" wire:navigate class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition text-sm">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Content -->
                <div class="p-6 print:p-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Order Summary -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Invoice Info -->
                            <div class="grid grid-cols-2 gap-4 print:text-sm">
                                <div>
                                    <p class="text-sm text-gray-500">Invoice Date</p>
                                    <p class="font-medium">{{ $order->created_at->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Order Status</p>
                                    <p class="font-medium capitalize">{{ $order->status }}</p>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 print:text-base">Order Items</h3>
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100 print:p-2 print:border-0 print:bg-transparent">
                                        @php
                                            $productImage = null;
                                            if ($item->product && $item->product->image) {
                                                $productImage = asset('storage/' . $item->product->image);
                                            }
                                        @endphp

                                        @if($productImage)
                                            <img src="{{ $productImage }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-20 h-20 rounded-lg object-cover print:w-16 print:h-16">
                                        @else
                                            <div class="w-20 h-20 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0 print:w-16 print:h-16">
                                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif

                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800 print:text-sm">{{ $item->product_name }}</h4>
                                            @if($item->product)
                                            <p class="text-xs text-gray-500 print:text-[10px]">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                            @endif
                                            <div class="flex flex-wrap items-center gap-4 mt-1 print:gap-2">
                                                <span class="text-sm text-gray-600 print:text-xs">Qty: {{ $item->quantity }}</span>
                                                <span class="text-sm font-semibold text-[#5B9F01] print:text-xs">${{ number_format($item->price, 2) }}</span>
                                                <span class="text-sm text-gray-500 print:text-xs">Subtotal: ${{ number_format($item->subtotal, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 print:text-base">Shipping Information</h3>
                                @if($order->shippingDetail)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 print:p-2 print:border-0 print:bg-transparent">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 print:text-sm">
                                        <div>
                                            <p class="text-sm text-gray-500">Address</p>
                                            <p class="font-medium text-gray-800">{{ $order->shippingDetail->address ?? 'N/A' }}</p>
                                            @if($order->shippingDetail->apartment)
                                            <p class="text-gray-600">{{ $order->shippingDetail->apartment }}</p>
                                            @endif
                                            <p class="text-gray-600">{{ $order->shippingDetail->city ?? 'N/A' }}, {{ $order->shippingDetail->state ?? 'N/A' }} {{ $order->shippingDetail->zip_code ?? 'N/A' }}</p>
                                            <p class="text-gray-600">{{ $order->shippingDetail->country ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Contact Information</p>
                                            <p class="font-medium text-gray-800">{{ $order->first_name }} {{ $order->last_name }}</p>
                                            <p class="text-gray-600">{{ $order->email }}</p>
                                            <p class="text-gray-600">{{ $order->phone }}</p>
                                        </div>
                                    </div>
                                    @if($order->shippingDetail->different_shipping)
                                    <div class="mt-4 pt-4 border-t border-gray-200 print:mt-2 print:pt-2">
                                        <p class="text-sm font-semibold text-gray-700">Different Shipping Address</p>
                                        <p class="text-gray-600">{{ $order->shippingDetail->shipping_address ?? 'N/A' }}</p>
                                        @if($order->shippingDetail->shipping_apartment)
                                        <p class="text-gray-600">{{ $order->shippingDetail->shipping_apartment }}</p>
                                        @endif
                                        <p class="text-gray-600">{{ $order->shippingDetail->shipping_city ?? 'N/A' }}, {{ $order->shippingDetail->shipping_state ?? 'N/A' }} {{ $order->shippingDetail->shipping_zip_code ?? 'N/A' }}</p>
                                        <p class="text-gray-600">{{ $order->shippingDetail->shipping_country ?? 'N/A' }}</p>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- Order Notes -->
                            @if($order->order_notes)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2 print:text-base">Order Notes</h3>
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 print:p-2 print:bg-transparent print:border-0">
                                    <p class="text-gray-700">{{ $order->order_notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Order Summary Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 sticky top-24 print:bg-transparent print:border-0 print:p-2 print:sticky print:top-0">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 print:text-base">Order Summary</h3>

                                <div class="space-y-3 print:text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping</span>
                                        <span class="font-medium">{{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tax</span>
                                        <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                                    </div>
                                    <div class="border-t pt-3 mt-3 print:pt-2 print:mt-2">
                                        <div class="flex justify-between text-lg font-bold print:text-base">
                                            <span>Total</span>
                                            <span class="text-[#5B9F01]">${{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Information -->
                                <div class="mt-6 pt-6 border-t border-gray-200 print:mt-4 print:pt-4">
                                    <div class="space-y-2 print:text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Payment Method</span>
                                            <span class="font-medium capitalize">{{ $order->payment_method }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Payment Status</span>
                                            <span class="font-medium capitalize {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Timeline - Hide on print -->
                                <div class="mt-6 pt-6 border-t border-gray-200 no-print">
                                    <h4 class="font-semibold text-gray-800 mb-3">Order Status</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'text-green-600' : 'text-gray-500' }}">Order Placed</p>
                                                <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>

                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'text-green-600' : 'text-gray-500' }}">Processing</p>
                                                <p class="text-xs text-gray-400">Order is being processed</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }}">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium {{ in_array($order->status, ['shipped', 'delivered']) ? 'text-green-600' : 'text-gray-500' }}">Shipped</p>
                                                <p class="text-xs text-gray-400">Order has been shipped</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($order->status == 'delivered')
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-green-500">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-green-600">Delivered</p>
                                                <p class="text-xs text-gray-400">Order has been delivered</p>
                                            </div>
                                        </div>
                                        @endif

                                        @if($order->status == 'cancelled')
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-red-500">
                                                <i class="fas fa-times text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-red-600">Cancelled</p>
                                                <p class="text-xs text-gray-400">Order has been cancelled</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons - Hidden on print -->
                                <div class="mt-6 pt-6 border-t border-gray-200 space-y-2 no-print">
                                   
                                    @if($order->status == 'delivered')
                                    <button class="w-full border border-[#5B9F01] text-[#5B9F01] px-4 py-2 rounded-lg hover:bg-[#5B9F01]/10 transition text-sm font-semibold">
                                        <i class="fas fa-redo mr-2"></i> Reorder Items
                                    </button>
                                    @endif
                                </div>

                                <!-- Invoice Footer - Show on print -->
                                <div class="mt-6 pt-6 border-t border-gray-200 print:block hidden text-center text-xs text-gray-500">
                                    <p>Thank you for your business!</p>
                                    <p>For any queries, contact us at support@greenjack.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Footer - Show on print -->
                <div class="hidden print:block bg-gray-50 px-6 py-4 text-center text-xs text-gray-500 border-t border-gray-200">
                    <p>This is a computer-generated invoice. No signature required.</p>
                    <p>Green Jack Store - All Rights Reserved</p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
            <i class="fas fa-exclamation-circle text-gray-300 text-6xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-800">Order Not Found</h2>
            <p class="text-gray-500 mt-2">The order you're looking for doesn't exist or you don't have permission to view it.</p>
            <a href="{{ route('customer.account') }}" wire:navigate class="inline-block mt-4 bg-[#5B9F01] text-white px-6 py-2 rounded-lg hover:bg-[#4a7f01] transition">
                Go to My Account
            </a>
        </div>
        @endif
    </div>

    <!-- Print JavaScript -->
    <script>
        function printInvoice() {
            // Get the invoice content
            var invoiceContent = document.getElementById('invoice-content');

            // Create a new window for printing
            var printWindow = window.open('', '_blank', 'width=800,height=600');

            // Get the styles
            var styles = document.querySelector('style').innerHTML;

            // Write the HTML content
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice #{{ $order->order_number ?? 'N/A' }}</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <style>
                        /* Reset styles */
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }

                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            background: white;
                            padding: 20px;
                            color: #333;
                        }

                        /* Print styles */
                        .print\\:block {
                            display: block !important;
                        }

                        .print\\:hidden {
                            display: none !important;
                        }

                        .print\\:bg-\\[\\#00349A\\] {
                            background-color: #00349A !important;
                        }

                        .print\\:p-4 {
                            padding: 16px !important;
                        }

                        .print\\:p-2 {
                            padding: 8px !important;
                        }

                        .print\\:text-sm {
                            font-size: 14px !important;
                        }

                        .print\\:text-base {
                            font-size: 16px !important;
                        }

                        .print\\:border-0 {
                            border: 0 !important;
                        }

                        .print\\:bg-transparent {
                            background: transparent !important;
                        }

                        .print\\:w-16 {
                            width: 64px !important;
                        }

                        .print\\:h-16 {
                            height: 64px !important;
                        }

                        .print\\:gap-2 {
                            gap: 8px !important;
                        }

                        .print\\:text-xs {
                            font-size: 12px !important;
                        }

                        .print\\:text-\\[10px\\] {
                            font-size: 10px !important;
                        }

                        .print\\:mt-2 {
                            margin-top: 8px !important;
                        }

                        .print\\:pt-2 {
                            padding-top: 8px !important;
                        }

                        .print\\:mt-4 {
                            margin-top: 16px !important;
                        }

                        .print\\:pt-4 {
                            padding-top: 16px !important;
                        }

                        .print\\:sticky {
                            position: sticky !important;
                        }

                        .print\\:top-0 {
                            top: 0 !important;
                        }

                        /* Hide non-print elements */
                        .no-print {
                            display: none !important;
                        }

                        /* Invoice styles */
                        .invoice-container {
                            max-width: 1000px;
                            margin: 0 auto;
                            background: white;
                        }

                        .invoice-header {
                            background: #00349A;
                            color: white;
                            padding: 20px;
                            border-radius: 8px 8px 0 0;
                        }

                        .invoice-body {
                            padding: 20px;
                        }

                        .invoice-footer {
                            padding: 15px 20px;
                            text-align: center;
                            border-top: 1px solid #e5e7eb;
                            font-size: 12px;
                            color: #6b7280;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        table th {
                            background: #f3f4f6;
                            padding: 10px;
                            text-align: left;
                            font-weight: 600;
                            border-bottom: 2px solid #e5e7eb;
                        }

                        table td {
                            padding: 10px;
                            border-bottom: 1px solid #e5e7eb;
                        }

                        .summary-row {
                            display: flex;
                            justify-content: space-between;
                            padding: 6px 0;
                        }

                        .total-row {
                            border-top: 2px solid #e5e7eb;
                            padding-top: 12px;
                            margin-top: 12px;
                            font-weight: bold;
                            font-size: 18px;
                        }

                        @media print {
                            body {
                                padding: 0;
                            }

                            .invoice-header {
                                border-radius: 0;
                            }

                            .no-print {
                                display: none !important;
                            }

                            .print\\:block {
                                display: block !important;
                            }

                            .print\\:hidden {
                                display: none !important;
                            }

                            .break-page {
                                page-break-after: avoid;
                            }

                            .invoice-container {
                                box-shadow: none !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-container">
                        <!-- Copy the invoice content -->
                        ${invoiceContent.innerHTML}
                    </div>

                    <script>
                        // Auto print when loaded
                        window.onload = function() {
                            window.print();
                            // Close the window after printing (or stay open)
                            // window.close();
                        };
                    <\/script>
                </body>
                </html>
            `);

            printWindow.document.close();
        }

        // Override the onclick to use the print function
        document.addEventListener('DOMContentLoaded', function() {
            var printBtn = document.querySelector('[onclick="printInvoice()"]');
            if (printBtn) {
                printBtn.onclick = function(e) {
                    e.preventDefault();
                    printInvoice();
                };
            }
        });
    </script>

    <style>
        /* Hide elements when printing */
        @media print {
            .no-print {
                display: none !important;
            }

            .print\\:block {
                display: block !important;
            }

            .print\\:hidden {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-gradient-to-br {
                background: #00349A !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .sticky {
                position: static !important;
            }

            .shadow-lg, .shadow-sm, .shadow-xl {
                box-shadow: none !important;
            }

            .border, .border-t, .border-gray-200, .border-gray-100 {
                border-color: #e5e7eb !important;
            }

            .bg-gray-50 {
                background-color: #f9fafb !important;
            }
        }



        @media print {
            .sticky {
                position: relative !important;
                top: 0 !important;
            }
        }
    </style>
</div>
