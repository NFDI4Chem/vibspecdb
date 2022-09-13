<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-2 bg-gray-600">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 items-center hidden md:inline">
                    <p class="ml-3 cookie-consent__message text-center md:text-left text-white text-sm leading-tight md:pr-12">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <a class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center rounded-md py-2 px-8 bg-green-500 hover:bg-green-600 text-white font-bold text-sm shadow-xl">
                        {{ trans('cookie-consent::texts.agree') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>