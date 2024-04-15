<?php 
namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\{Bucket, Ball, BucketBallCount};
use DB;
use Auth;


trait BasketBallResultTraits{

	public function getResultOfBucketBalls()
    {
        $user_id = Auth::user()->id;

            $bucketCounts = DB::select("
                SELECT bb.bucket_id, b.name AS ball_name, COUNT(*) AS ball_count
                FROM bucket_ball_counts bb
                INNER JOIN balls b ON bb.ball_id = b.id
                INNER JOIN buckets bu ON bb.bucket_id = bu.id
                WHERE bb.user_id = ?
                GROUP BY bb.bucket_id, b.name
            ", [$user_id]);

            $result = [];

            foreach ($bucketCounts as $row) {
                $bucket_id = $row->bucket_id;
                $ball_name = $row->ball_name;
                $ball_count = $row->ball_count;

                $bucket_name = DB::table('buckets')->where('id', $bucket_id)->value('name');

                $result[] = [
                    'bucket_name' => $bucket_name,
                    'ball_name' => $ball_name,
                    'ball_count' => $ball_count
                ];
            }

            return view('/getBacketsBallResutl', compact('result'))->render();
    }


}