@once

    <script src="https://tnpg.moamalat.net:6006/js/lightbox.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>

    <script>
        class MoamalataPay {
            constructor(MID, TID, key, amount, merchantReference = "", debug = false) {
                this.MID = MID;
                this.TID = TID;
                this.key = this.hex_to_str(key);
                this.amount = amount;
                this.merchantReference = merchantReference;
                this.debug = debug;
                this.dateTimeLocalTrxn = null;
            }


            log(data) {
                if (this.debug) {
                    console.log(data);
                }
            }

            hex_to_str(hex) {
                var str = "";
                for (var i = 0; i < hex.length; i += 2)
                    str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
                return str;
            }

            set_datetimelocaltrxn() {
                console.log("set_datetime");
                this.dateTimeLocalTrxn = Number.parseInt(Date.now() / 1000).toString();
            }

            encode_data() {
                return `Amount=${this.amount}&DateTimeLocalTrxn=${this.dateTimeLocalTrxn}&MerchantId=${this.MID}&MerchantReference=${this.merchantReference}&TerminalId=${this.TID}`;
            }

            get_secure_hash() {
                var data = this.encode_data();
                var hash = CryptoJS.HmacSHA256(data, this.key).toString().toUpperCase();
                this.log({
                    data,
                    hash,
                    key: this.key
                });
                return hash;
            }

            pay(amount = null, merchantReference = null) {

                if (amount !== null) {
                    this.amount = amount;
                }
                if (merchantReference !== null) {
                    this.merchantReference = merchantReference;
                }

                this.log("Starting pay");

                this.set_datetimelocaltrxn();

                let parent_ = this;

                Lightbox.Checkout.configure = {
                    MID: this.MID,
                    TID: this.TID,
                    AmountTrxn: this.amount,
                    MerchantReference: this.merchantReference,
                    TrxDateTime: this.dateTimeLocalTrxn,
                    SecureHash: this.get_secure_hash(),
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
                        parent_.log({
                            "status": "canceled"
                        });
                    },
                };

                Lightbox.Checkout.showLightbox();
            }
        }


        let _moamalatPay = new MoamalataPay(
            "{{ config('moamalat-pay.merchant_id') }}",
            "{{ config('moamalat-pay.terminal_id') }}",
            "{{ config('moamalat-pay.key') }}",
            0,
            "",
            "{{ config('moamalat-pay.show_logs') }}",
        )

        {{-- /* if user set amount , then call pay */ --}}
        @if (isset($amount) && $amount)
            _moamalatPay.pay({{ $amount }}, "{{ $reference }}")
        @endif
    </script>


@endonce
