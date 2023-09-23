<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Url Shortener</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

  @stack('page_style')
</head>

<body>
  @yield('content')

  @stack('page_script')
  <script>
    function copyText(value) {
        navigator.clipboard.writeText(value).then(() => {
            alert(`Link copied to clipboard`);
        }).catch(err => {
            alert(`Failed to copy link to clipboard`);
        });
    }
  </script>
</body>

</html>