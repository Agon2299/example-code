<img src="{{ asset('/vendor/nova/icon.png') }}" alt="logo" class="my-logo {{ mb_strpos ( URL::current(), 'login' ) ? 'login' : '' }}"> {{ \Laravel\Nova\Nova::name() }}