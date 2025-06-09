@extends('layouts.app')
@section('title','Login History')
@section('content')
<div class="bg-white p-6 rounded shadow overflow-x-auto">
  <h2 class="text-2xl font-bold mb-4">Login History</h2>
  <table class="w-full text-left">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-4 py-2">Email</th>
        <th class="px-4 py-2">IP Address</th>
        <th class="px-4 py-2">Logged At</th>
      </tr>
    </thead>
    <tbody>
      @foreach($histories as $h)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $h->user->email }}</td>
          <td class="px-4 py-2">{{ $h->ip_address }}</td>
          <td class="px-4 py-2">{{ $h->created_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="mt-4">{{ $histories->links() }}</div>
</div>
@endsection
