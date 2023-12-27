@once

    @if (config('moamalat-pay.production'))
        <script src="https://npg.moamalat.net:6006/js/lightbox.js"></script>
    @else
        <script src="https://tnpg.moamalat.net:6006/js/lightbox.js"></script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>

    <script>
        if (!window.dispatchEvent) {

            class MoamalataPay {
                constructor(MID, TID, amount, merchantReference = "", debug = false) {
                    this.MID = MID;
                    this.TID = TID;
                    this.amount = amount;
                    this.merchantReference = merchantReference;
                    this.debug = debug;
                }

                log(data) {
                    if (this.debug) {
                        console.log(data);
                    }
                }

                async fetchSecureHash() {
                    const url = new URL("{{ route(config('moamalat-pay.generate-securekey.route_name')) }}");
                    url.searchParams.set('MID', this.MID);
                    url.searchParams.set('TID', this.TID);
                    url.searchParams.set('amount', this.amount);
                    url.searchParams.set('merchantReference', this.merchantReference);
                    try {
                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`Failed to fetch secureHash from server. Status: ${response.status}`);
                        }
                        return await response.json();
                    } catch (error) {
                        console.error("An error occurred:", error);
                        throw error;
                    }
                }

                async pay(amount = null, merchantReference = null) {

                    if (amount !== null) {
                        this.amount = amount;
                    }
                    if (merchantReference !== null) {
                        this.merchantReference = merchantReference;
                    }

                    this.log("Starting pay , mode => {{ config('moamalat-pay.production') ? 'produciton' : 'test' }}");

                    let parent_ = this;

                    const secureHashResponse = await this.fetchSecureHash();

                    Lightbox.Checkout.configure = {
                        MID: this.MID,
                        TID: this.TID,
                        AmountTrxn: this.amount,
                        MerchantReference: this.merchantReference,
                        TrxDateTime: secureHashResponse.DateTimeLocalTrxn,
                        SecureHash: secureHashResponse.secureHash,
                        completeCallback: function(data) {
                            window.dispatchEvent(
                                new CustomEvent('moamalatCompleted', {
                                    detail: data
                                })
                            )
                            parent_.log({
                                "status": "completed",
                                data
                            });
                        },
                        errorCallback: function(error) {
                            window.dispatchEvent(
                                new CustomEvent('moamalatError', {
                                    detail: error,
                                    ee: error,
                                })
                            )
                            parent_.log({
                                "status": "error",
                                error
                            });
                        },
                        cancelCallback: function() {
                            window.dispatchEvent(
                                new CustomEvent('moamalatCancel')
                            )
                            parent_.log({
                                "status": "canceled"
                            });
                        },
                    };

                    Lightbox.Checkout.showLightbox();
                }
            }


            const _moamalatPay = new MoamalataPay(
                "{{ config('moamalat-pay.merchant_id') }}",
                "{{ config('moamalat-pay.terminal_id') }}",
                0,
                "",
                "{{ config('moamalat-pay.show_logs') }}",
            )

            {{-- /* if user set amount , then call pay */ --}}
            @if (isset($amount) && $amount)
                _moamalatPay.pay({{ $amount }}, "{{ $reference ?? '' }}");
            @endif

        } else {
            alert("Your browser is too old to support this feature. Please update your browser.")
        }
    </script>


@endonce
