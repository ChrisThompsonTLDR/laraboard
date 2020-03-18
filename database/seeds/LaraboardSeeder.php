<?php

namespace Christhompsontldr\Laraboard\Database\Seeds;

use Illuminate\Database\Seeder;
use DB;
use Faker\Factory;
use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Category;
use Christhompsontldr\Laraboard\Models\Thread;
use App\User;
use Carbon\Carbon;

class LaraboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        //  create some categories
        foreach (range(1,rand(3, 7)) as $c) {
            $category             = new Category;
            $category->name       = ucwords(str_replace('.', '', ($faker->sentence(rand(1, 3)))));
            $category->body       = (rand(1,6) == 6) ? $faker->sentence : null;
            $category->user_id    = User::inRandomOrder()->first()->id;
            $category->ip         = $faker->ipv4;
            $category->created_at = now()->subMonths(rand(6,24))->subHours(rand(1,24))->subMinutes(rand(1,59));
            $category->updated_at = $category->created_at;
            $category->save();

            //  1 in 10 chance there are no boards
            if (rand(1,10) == 10) { continue; }

            $previous_board = $previous_thread = $previous_reply = $category;

            //  make some boards
            foreach (range(1,rand(2,3)) as $b) {
                $board             =  new Post;
                $board->name       = ucwords(str_replace('.', '', ($faker->sentence(rand(1, 5)))));
                $board->body       = (rand(1,6) == 6) ? $faker->sentence : null;
                $board->type       = 'Board';
                $board->user_id    = User::inRandomOrder()->first()->id;
                $board->ip         = $faker->ipv4;
                $board->created_at = Carbon::parse($previous_board->created_at)->addMinutes(rand(1,500));
                $board->updated_at = $board->created_at;
                $board->save();
                $board->makeChildOf($category);

                //  1 in 10 chance there are no threads
                if (rand(1,10) == 10) { continue; }

                $previous_board = $previous_thread = $previous_reply = $board;

                //  make some threads
                foreach (range(1,rand(1,20)) as $b) {
                    $rand = (($b == 1) ? rand(1,60) : rand(1,300));

                    $thread             = new Post;
                    $thread->name       = $faker->sentence(rand(3, 10));
                    $thread->body       = implode("\n\n", $faker->paragraphs($faker->randomDigitNotNull));
                    $thread->type       = 'Thread';
                    $thread->user_id    = User::inRandomOrder()->first()->id;
                    $thread->ip         = $faker->ipv4;
                    $thread->created_at = Carbon::parse($previous_thread->created_at)->addMinutes($rand);
                    $thread->updated_at = $thread->created_at;
                    $thread->save();
                    $thread->makeChildOf($board);

                    $previous_thread = $previous_reply = $thread;

                    //  1 in 10 chance there are no replies
                    if (rand(1,10) == 10) { continue; }

                    //  make some replies
                    foreach (range(1,rand(1,50)) as $r) {
                        $reply             = new Post;
                        $reply->body       = file_get_contents('https://jaspervdj.be/lorem-markdownum/markdown.txt?num-blocks=' . rand(1,6) . '&no-headers=true');
                        $reply->type       = 'Reply';
                        $reply->user_id    = User::inRandomOrder()->first()->id;
                        $reply->ip         = $faker->ipv4;
                        $reply->created_at = Carbon::parse($previous_reply->created_at)->addMinutes($rand);
                        $reply->updated_at = $reply->created_at;
                        $reply->save();
                        $reply->makeChildOf($thread);

                        $previous_reply = $reply;
                    }
                }
            }
        }
    }
}
