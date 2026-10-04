<div>
    <style>
        /* Additional modern touches: smooth scroll, custom selection */
        ::selection {
            background: rgba(91, 159, 1, 0.3);
            color: #1f2a2e;
        }
        .btn-primary i, .learn-link i {
            transition: transform 0.2s;
        }
        .print-card:hover .icon-circle {
            border-radius: 30px;
        }
        p.text-gray-500 {
            color: #5d6e73;
        }
        .max-w-2xl {
            max-width: 42rem;
        }
        .services-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8faf7 100%);
            position: relative;
            overflow: hidden;
        }

        .services-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(#e3f0d5 1.2px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.4;
            pointer-events: none;
        }

        /* header style matching modern brand */
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(91, 159, 1, 0.12);
            padding: 6px 18px;
            border-radius: 60px;
            margin-bottom: 20px;
            backdrop-filter: blur(2px);
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background-color: #5B9F01;
            border-radius: 50%;
            display: inline-block;
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0% { opacity: 0.4; transform: scale(0.8);}
            100% { opacity: 1; transform: scale(1.2);}
        }

        .section-badge span {
            color: #3f6d00;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.4rem;
            font-weight: 700;
            color: #1E2A2F;
            margin-bottom: 16px;
        }

        .title-decoration {
            width: 70px;
            height: 4px;
            background: #5B9F01;
            border-radius: 4px;
            margin: 0 auto;
            margin-top: 8px;
        }

        /* G R I D  -  4 columns + responsive */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 28px;
            margin: 48px 0 40px;
        }

        @media (min-width: 640px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Service Card - refined similar but distinct: softer shadows, modern borders */
        .print-card {
            background: white;
            border-radius: 28px;
            padding: 28px 20px 32px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 12px 26px -12px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(91, 159, 1, 0.08);
            position: relative;
            z-index: 2;
        }

        .print-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 40px -20px rgba(91, 159, 1, 0.25), 0 12px 24px -8px rgba(0, 0, 0, 0.08);
            border-color: rgba(91, 159, 1, 0.25);
        }

        /* Icon container - different curvature: circular modern */
        .icon-circle {
            width: 84px;
            height: 84px;
            background: #F0F7E5;
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            transition: all 0.3s ease;
        }

        .print-card:hover .icon-circle {
            background: #5B9F01;
            transform: scale(0.96);
            border-radius: 32px;
        }

        .icon-circle i {
            font-size: 42px;
            color: #5B9F01;
            transition: all 0.3s ease;
        }

        .print-card:hover .icon-circle i {
            color: white;
            transform: scale(1.02);
        }

        .service-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 14px;
            color: #1f2a2e;
            height:50px;
        }

        .service-title a {
            text-decoration: none;
            color: inherit;
            transition: color 0.2s;
        }

        .service-title a:hover {
            color: #5B9F01;
        }

        .card-description {
            color: #5b6b6f;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 22px;
            padding: 0 4px;
        }

        .learn-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #5B9F01;
            text-decoration: none;
            border-bottom: 1.5px solid transparent;
            transition: all 0.2s;
        }

        .learn-link i {
            font-size: 0.8rem;
            transition: transform 0.2s;
        }

        .learn-link:hover {
            gap: 12px;
            border-bottom-color: #5B9F01;
        }

        .learn-link:hover i {
            transform: translateX(4px);
        }

        /* CTA button enhanced */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: #5B9F01;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 32px;
            border-radius: 50px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 14px rgba(91, 159, 1, 0.3);
            text-decoration: none;
            border: none;
        }

        .btn-primary:hover {
            background: #3f7a00;
            transform: scale(1.02);
            box-shadow: 0 12px 20px -8px rgba(91, 159, 1, 0.5);
            gap: 14px;
        }

        /* extra decorative elements */
        .floating-accent {
            position: absolute;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(91,159,1,0.03) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .accent-1 {
            top: -80px;
            left: -60px;
        }
        .accent-2 {
            bottom: -60px;
            right: -40px;
        }

        @media (max-width: 768px) {
            .services-section {
                padding: 60px 0;
            }
            h2 {
                font-size: 1.9rem;
            }
            .icon-circle {
                width: 72px;
                height: 72px;
            }
            .icon-circle i {
                font-size: 34px;
            }
            .service-title {
                font-size: 1.3rem;
            }
        }
    </style>

    <section class="services-section">
        <div class="floating-accent accent-1"></div>
        <div class="floating-accent accent-2"></div>
        <div class="container" style="max-width: 1280px; margin: 0 auto; padding: 0 24px;">
            <!-- Section Header -->
            <div class="text-center">
                <div class="section-badge">
                    <span class="badge-dot"></span>
                    <span>PREMIUM SOLUTIONS</span>
                </div>
                <h2>Print. Promote. <span style="color:#5B9F01;">Perform.</span></h2>
                <div class="title-decoration"></div>
                <p class="text-gray-500 max-w-2xl mx-auto mt-4" style="max-width: 560px; color: #5f6f74;">
                    High‑impact printing & strategic marketing — from business essentials to bold campaigns.
                </p>
            </div>
            <!-- Success Message -->
                    @if($successMessage)
                        <div class="mt-4 text-center bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            ✅ {{ $successMessage }}
                        </div>
                    @endif

            <!-- Services Grid: 8 distinct printing & marketing services with matching icons -->
            <div class="services-grid">
            @foreach($services as $serviceName => $serviceDesc)
            <div class="print-card" wire:key="{{ $loop->index }}">
                <div class="icon-circle">
                    @switch($serviceName)
                        @case('Document Printing') <i class="fas fa-file-alt"></i> @break
                        @case('Business Cards') <i class="fas fa-id-card"></i> @break
                        @case('Signs, Banners & Posters') <i class="fas fa-sign"></i> @break
                        @case('Marketing Materials') <i class="fas fa-chart-line"></i> @break
                        @case('Cards & Invitations') <i class="fas fa-envelope-open-text"></i> @break
                        @case('Labels & Stickers') <i class="fas fa-tag"></i> @break
                        @case('Envelope & Stationery') <i class="fas fa-envelope"></i> @break
                        @case('Photo Gifts & Business Solutions') <i class="fas fa-gift"></i> @break
                        @default <i class="fas fa-print"></i>
                    @endswitch
                </div>
                <h3 class="service-title"><a href="javascript:void(0)" wire:click.prevent="openModal('{{ $serviceName }}', '{{ addslashes($serviceDesc) }}')">{{ $serviceName }}</a></h3>
                <p class="card-description">{{ Str::limit($serviceDesc, 100) }}</p>
                <a href="javascript:void(0)" class="learn-link" wire:click.prevent="openModal('{{ $serviceName }}', '{{ addslashes($serviceDesc) }}')">Get Quote <i class="fas fa-arrow-right"></i></a>
            </div>
            @endforeach
        </div>


        </div>
    </section>

    <!-- Modal Structure -->
<!-- Modal -->
    @if($showModal)
    <div id="serviceModal" class="modal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
        <div class="modal-content" style="background: white; max-width: 600px; width: 100%; margin: auto; border-radius: 32px; padding: 28px 24px; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); animation: modalFadeIn 0.3s ease; max-height: 90vh; overflow-y: auto; box-sizing: border-box;">

            <button type="button" class="modal-close" wire:click="closeModal" style="position: sticky; top: 0; float: right; background: white; border: none; font-size: 28px; cursor: pointer; color: #999; transition: 0.2s; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: -8px -8px 0 0;">&times;</button>
            <div style="clear: both;"></div>

            <div class="modal-header" style="margin-bottom: 20px; padding-right: 20px;">
                <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.6rem; color: #1E2A2F; margin: 0 0 8px 0; line-height: 1.3; word-break: break-word;">{{ $modalServiceTitle }}</h3>
                <div style="width: 50px; height: 3px; background: #5B9F01; border-radius: 4px;"></div>
            </div>

            <div class="modal-body">
                <p style="color: #5b6b6f; line-height: 1.5; margin-bottom: 24px; font-size: 0.95rem;">{{ $modalServiceDesc }}</p>

                <form wire:submit.prevent="submitInquiry" style="width: 100%;">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-weight: 600; color: #1f2a2e; margin-bottom: 6px; font-size: 0.9rem;">Full Name *</label>
                        <input type="text" wire:model="name" style="width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 16px; font-size: 1rem; transition: 0.2s; outline: none; box-sizing: border-box;">
                        @error('name') <span style="color: #d9534f; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-weight: 600; color: #1f2a2e; margin-bottom: 6px; font-size: 0.9rem;">Email Address *</label>
                        <input type="email" wire:model="email" style="width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 16px; font-size: 1rem; transition: 0.2s; outline: none; box-sizing: border-box;">
                        @error('email') <span style="color: #d9534f; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-weight: 600; color: #1f2a2e; margin-bottom: 6px; font-size: 0.9rem;">Phone Number</label>
                        <input type="tel" wire:model="phone" style="width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 16px; font-size: 1rem; transition: 0.2s; outline: none; box-sizing: border-box;">
                        @error('phone') <span style="color: #d9534f; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; color: #1f2a2e; margin-bottom: 6px; font-size: 0.9rem;">Service *</label>
                        <input type="text" wire:model="service" readonly style="width: 100%; padding: 12px 14px; background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 16px; font-size: 0.95rem; color: #2c3e2f; font-weight: 500; box-sizing: border-box; cursor: default;">
                        @error('service') <span style="color: #d9534f; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 600; color: #1f2a2e; margin-bottom: 6px; font-size: 0.9rem;">Message / Requirements</label>
                        <textarea wire:model="message" rows="4" placeholder="Tell us about your project, quantity, or deadline..." style="width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 20px; font-family: inherit; resize: vertical; font-size: 0.9rem; box-sizing: border-box;"></textarea>
                        @error('message') <span style="color: #d9534f; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" style="background: #5B9F01; color: white; border: none; padding: 14px 28px; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; width: 100%; transition: 0.2s; box-shadow: 0 4px 12px rgba(91,159,1,0.3);">
    <span wire:loading.remove wire:target="submitInquiry">Send Inquiry →</span>
    <span wire:loading wire:target="submitInquiry">Sending...</span>
</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal input:focus, .modal textarea:focus {
            border-color: #5B9F01;
            box-shadow: 0 0 0 3px rgba(91,159,1,0.1);
            outline: none;
        }

        .modal-close:hover {
            color: #5B9F01 !important;
            transform: scale(1.1);
            background: #f5f5f5;
        }

        @media (max-width: 640px) {
            .modal-content {
                padding: 20px 16px !important;
                max-height: 85vh !important;
            }

            .modal-header h3 {
                font-size: 1.4rem !important;
            }

            .modal input, .modal textarea {
                font-size: 16px !important;
            }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-modal', () => {
                document.body.style.overflow = 'hidden';
            });

            Livewire.on('hide-modal', () => {
                document.body.style.overflow = '';
            });

            Livewire.on('show-success', (message) => {
                alert(message[0]);
            });

            Livewire.on('show-error', (message) => {
                alert(message[0]);
            });
        });
    </script>
    @endpush

</div>
