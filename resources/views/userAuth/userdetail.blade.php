@extends('adminview.dashboard')

@section('title', 'User Details')

@section('content')
    <div class="container-fluid" style="max-width: 720px;">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">User Details</h4>
                <p class="text-muted small mb-0">View full information about this user</p>
            </div>
            <a href="{{ route('users') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="user-avatar-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1 fw-semibold">{{ $user->name }}</h5>
                        <span class="text-muted small">{{ $user->email }}</span>
                    </div>

                    <div class="ms-auto">
                        @if($user->is_active)
                            <span class="badge bg-success-subtle text-success">
                                <i class="fa-solid fa-circle-check me-1"></i> Active
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                            </span>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- Detail Rows --}}
                <div class="row gy-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">User ID</p>
                        <p class="fw-medium mb-0">#{{ $user->id }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Role</p>
                        <p class="fw-medium mb-0">
                            @if($user->user_type === 'admin')
                                <span class="badge bg-primary-subtle text-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">User</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Full Name</p>
                        <p class="fw-medium mb-0">{{ $user->name }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Email Address</p>
                        <p class="fw-medium mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Account Status</p>
                        <p class="fw-medium mb-0">{{ $user->is_active ? 'Active' : 'Disabled' }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Joined Date</p>
                        <p class="fw-medium mb-0">{{ $user->created_at?->format('d M Y, h:i A') }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Last Updated</p>
                        <p class="fw-medium mb-0">{{ $user->updated_at?->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{--===== Actions =====--}}
        <div class="d-flex gap-2 justify-content-end mt-4">
            @if($canManage)
                <a href="{{ route('editpage', $user->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit User
                </a>

                <form action="{{ route('userstatus', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-warning">
                        @if($user->is_active == 1)
                            <i class="fa-solid fa-ban me-1"></i> Disable
                        @else
                            <i class="fa-solid fa-check me-1"></i> Activate
                        @endif
                    </button>
                </form>

                <form action="{{ route('userdelete', $user->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger delete-btn">
                        <i class="fa-solid fa-trash me-1"></i> Delete
                    </button>
                </form>
            @else
                <span class="text-muted fst-italic">
                    <i class="fa-solid fa-lock me-1"></i>
                    @if(Auth::id() === $user->id)
                        You cannot modify your own account from here.
                    @else
                        You don't have permission to manage this user.
                    @endif
                </span>
            @endif
        </div>
        {{--====== End Actions =====--}}


    </div>
@endsection

@section('scripts')
    <style>
        .user-avatar-lg {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.15) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.15) !important;
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.15) !important;
        }

        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.15) !important;
        }
    </style>

    
    </script>
@endsection