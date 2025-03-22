<x-app-layout>
    
    @include('profile.partials.nav-profile')

    @include('profile.partials.notification')

    <div class="columns is-multiline">

        <div class="column is-full">

            <div class="card">
                <div class="card-content">
                    <h2 class="title is-4 mb-4 pb-3">Payment Information</h2>

                    <!-- <div class="columns is-multiline">
                        <div class="column is-one-third">
                            <div class="card platform is-active">
                                <div class="card-content">
                                    <div class="is-flex is-justify-content-center">
                                        <strong class="is-block">Paypal</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-one-third">
                            <div class="card platform">
                                <div class="card-content">
                                    <div class="is-flex is-justify-content-center">
                                        <strong class="is-block">Stripe</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <form id="formProfilePayment" action="" method="post">
                        @csrf
                        @method('patch')

                        <!-- Campo: Phone Numbers -->
                        <div class="columns is-multiline">

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="paypal_email">Email Acount</label>
                                    <div class="control">
                                        <input
                                            id="paypal_email"
                                            class="input"
                                            type="email"
                                            name="paypal_email"
                                            value="{{$billing->bill_email_account?? ''}}"
                                            >
                                    </div>
                                </div>
                            </div>

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="bank_name">Bank</label>
                                    <div class="control">
                                        <input
                                            id="bank_name"
                                            class="input"
                                            type="text"
                                            name="bank_name"
                                            value="{{ $billing->bill_bank?? ''}}"
                                            >
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="field mb-4">
                            <label class="label" for="accounNumber">Account Number</label>
                            <div class="control">
                                <input
                                    id="accounNumber"
                                    class="input"
                                    type="text"
                                    name="accounNumber"
                                    value="{{ $billing->bill_account?? ''}}"
                                    required
                                    >
                            </div>
                        </div>

                        <!-- Campo: Phone Numbers -->
                        <div class="columns is-multiline">

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="banckCountUs">Bank Acount Us</label>
                                    <div class="control">
                                            <div class="select is-fullwidth">
                                                <select id="banckCountUs" name="banckCountUs">
                                                    <option value="1" {{ (isset($billing) && ($billing->bill_usa_bank_account === 1 || $billing->bill_usa_bank_account === null || $billing->bill_usa_bank_account === '')) || !isset($billing) ? 'selected' : '' }}>Si</option>
                                                    <option value="0" {{ isset($billing) && $billing->bill_usa_bank_account === 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="swift">Swift</label>
                                    <div class="control">
                                        <input
                                            id="swift"
                                            class="input"
                                            type="text"
                                            name="swift"
                                            value="{{ $billing->bill_swift?? ''}}"
                                            >
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Campo: Phone Numbers -->
                        <div class="columns is-multiline">

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="routing">Routing</label>
                                    <div class="control">
                                        <input
                                            id="routing"
                                            class="input"
                                            type="text"
                                            name="routing"
                                            value="{{ $billing->bill_routing?? ''}}"
                                            >
                                    </div>
                                </div>
                            </div>

                            
                            <div class="column is-hidden">
                                <div class="field">
                                    <div class="control">
                                        <input
                                            id="id"
                                            class="input"
                                            type="text"
                                            name="id"
                                            value="{{$provider_auth}}"
                                            >
                                    </div>
                                </div>
                            </div>

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="platform">Platform</label>
                                    <div class="control">
                                            <div class="select is-fullwidth">
                                                <select id="platform" name="platform">
                                                    <option value="1" selected>PayPal</option>
                                                    <option value="2">Stripe</option>
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="field mt-4">
                            <div class="control">
                                <button id="btnProfilePayment" class="button mt-3 p-2 btn-general ">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36"><path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged"/><path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged"/><path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged"/><circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                    
                                    <span class="ml-1">{{ __('Save Changes') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>