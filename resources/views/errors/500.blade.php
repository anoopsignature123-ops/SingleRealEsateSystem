@include('errors.layout', [
    'code' => '500',
    'title' => 'Server Error',
    'message' => 'Something went wrong on our servers. Please try again later.'
])