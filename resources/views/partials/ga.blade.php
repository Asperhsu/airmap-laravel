@if (config('services.ga.id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga.id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('services.ga.id') }}');
</script>
@endif