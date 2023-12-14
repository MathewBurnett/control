@extends('layouts.app', [
    'activenav' => 'seating',
])

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seatingplans.index') }}">Seating Plans</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('seatingplans.show', $event->code) }}">{{ $event->name }}</a></li>
@endsection

@section('content')
    <div class="page-header mt-0">
        <h1>{{ $event->name }}</h1>
    </div>
    <div class="row">
        <div class="col-md-3">
            @include('seatingplans._tickets', [
                'title' => 'Your Tickets',
                'tickets' => $tickets[0]
            ])
            @foreach($clans as $clan)
                @include('seatingplans._tickets', [
                    'title' => $clan->name,
                    'tickets' => $tickets[$clan->id] ?? []
                ])
            @endforeach
        </div>
        <div class="col-md-9">
            <div class="card-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($event->seatingPlans as $i => $plan)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link @if($i === 0) active @endif" href="#tab-plan-{{ $plan->code }}" data-bs-toggle="tab" @if($i === 0) aria-selected="true" @endif role="tab">{{ $plan->name }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($event->seatingPlans as $i => $plan)
                        <div id="tab-plan-{{ $plan->code }}" class="card tab-pane @if($i === 0) active show @endif" role="tabpanel" style="min-width: {{ collect($seats[$plan->id] ?? [])->max('x') * 2 + 4 }}em;">
                            @include('seatingplans._plan')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('footer')
    <script type="text/javascript">
        const INTERVAL = 10;

        let plans = {
            @foreach($event->seatingPlans as $plan)
                '{{ $plan->code }}': {{ $plan->revision }},
            @endforeach
        }

        function updatePlan(code, version) {
            axios.get('{{ route('seatingplans.show', $event->code) }}', {
                params: {
                    plan: code,
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                responseType: 'text',
            })
            .then(response => {
                if (response.status !== 200) {
                    throw new Error('Unable to update seating plan');
                }
                return response.data;
            })
            .then(data => {
                let container = document.getElementById('tab-plan-' + code);
                container.innerHTML = data;
                plans[code] = version;
                container.querySelectorAll('[data-bs-toggle="popover"]').forEach((element) => {
                    new bootstrap.Popover(element);
                });
            });
        }

        function checkRevisions() {
            axios.get('{{ route('api.v1.events.seatingplans.index', ['event' => $event->code]) }}', {
                headers: {
                    'Accept': 'application/json',
                },
            }).then(response => {
                if (response.status !== 200) {
                    throw new Error('Unable to fetch revision');
                }
                return response.data;
            }).then(data => {
                data.data.forEach((plan) => {
                    if (plans[plan.code] && plans[plan.code] !== plan.revision) {
                        updatePlan(plan.code, plan.version);
                    }
                });
            }).finally(() => {
                setTimeout(checkRevisions, INTERVAL * 1000);
            })
        }

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(checkRevisions, INTERVAL * 1000)
        });
    </script>
@endpush
