<table class="table table-horizontal table-striped">
    <thead>
    <tr>
        <th>Tube</th>
        <th>current-jobs-urgent</th>
        <th>current-jobs-ready</th>
        <th>current-jobs-reserved</th>
        <th>current-jobs-delayed</th>
        <th>current-jobs-buried</th>
        <th>current-using</th>
        <th>current-watching</th>
        <th>total-jobs</th>
        <th>cmd-delete</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($tubes as $name => $stats)
        <tr>
            <td>
                <a href="{{ route('beanstalkd.tubes.show', ['tube' => $name]) }}">{{ $name }}</a>
            </td>

            <td>{{ $stats['currentJobsUrgent'] }}</td>
            <td>{{ $stats['currentJobsReady'] }}</td>
            <td>{{ $stats['currentJobsReserved'] }}</td>
            <td>{{ $stats['currentJobsDelayed'] }}</td>
            <td>{{ $stats['currentJobsBuried'] }}</td>
            <td>{{ $stats['currentUsing'] }}</td>
            <td>{{ $stats['currentWatching'] }}</td>
            <td>{{ $stats['totalJobs'] }}</td>
            <td>{{ $stats['cmdDelete'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
