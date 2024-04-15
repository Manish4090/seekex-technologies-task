@php
$aggregatedResult = [];
foreach ($result as $item) {
$bucketName = $item['bucket_name'];
$ballName = $item['ball_name'];
$ballCount = $item['ball_count'];

    if (!isset($aggregatedResult[$bucketName])) {
        $aggregatedResult[$bucketName] = [];
    }

    $found = false;
    foreach ($aggregatedResult[$bucketName] as &$ball) {
        if ($ball['ball_name'] === $ballName) {
            $ball['ball_count'] += $ballCount;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $aggregatedResult[$bucketName][] = [
            'ball_name' => $ballName,
            'ball_count' => $ballCount,
        ];
    }
}

@endphp
<h4 >Result</h4>
<table border="1" style="width:100%">
    <thead>
        <tr>
            <th>Bucket Name</th>
            <th>Ball Name</th>
            <th>Ball Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($aggregatedResult as $bucketName => $balls)
            @foreach ($balls as $ball)
                <tr>
                    <td>{{ $loop->first ? $bucketName : '' }}</td>
                    <td>{{ ucfirst($ball['ball_name']) }}</td>
                    <td>{{ $ball['ball_count'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>