<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to eSewa</title>
</head>
<body>
    <form id="esewaPaymentForm" action="{{ $epayUrl }}" method="POST">
        @foreach ($payload as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </form>

    <script>
        document.getElementById('esewaPaymentForm').submit();
    </script>
</body>
</html>
