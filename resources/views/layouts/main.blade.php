<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <title>@yield('title') | {{ $pengaturan->name ?? config('app.name') }}</title>

  {{-- Styling --}}
  @include('includes.style')
  @stack('style')
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg" style="background-color: #6868b5;"></div>
        {{-- Navbar --}}
        @include('partials.nav')


        {{-- Sidebar --}}
        @include('partials.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

      {{-- Footer --}}
      @include('partials.footer')
    </div>
  </div>

  {{-- Scripts --}}
  @include('includes.script')
  @stack('script')
</body>
</html>
