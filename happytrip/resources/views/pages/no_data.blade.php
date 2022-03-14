<x-page-layout :class="'mt-0'">
  @section('additionalstyles')

   <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@8af0edd/css/all.css%22"
   rel="stylesheet" type="text/css" />
    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="owl/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="owl/owlcarousel/assets/owl.theme.default.min.css">
 
  @endsection
  
    @include('pages.common._navbar')

	<div class="alert alert-danger" role="alert">
	    <strong>{{ __('Sorry') }} !</strong> {{ __('No Rooms Available Now') }} .
	</div>
</x-page-layout>
