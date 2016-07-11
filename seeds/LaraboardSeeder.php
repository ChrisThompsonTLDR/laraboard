<?php

use Illuminate\Database\Seeder;

class LaraboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('forum_posts')->truncate();

        $faker = Faker\Factory::create();

        //  create some categories
        foreach (range(1,rand(5,10)) as $c) {
            $category             = new \Christhompsontldr\Laraboard\Models\Post;
            $category->name       = $faker->sentence(20);
            $category->slug       = str_slug($category->name);
            $category->body       = $faker->sentence;
            $category->type       = 'Category';
            $category->user_id    = \App\Role::where('name', 'admin')->first()->users->random()->first()->id;
            $category->ip         = $faker->ipv4;
            $category->created_at = \Carbon\Carbon::now()->subMonths(rand(6,24))->subHours(rand(1,24))->subMinutes(rand(1,59));
            $category->updated_at = $category->created_at;
            $category->save();

            //  1 in 10 chance there are no boards
            if (rand(1,10) == 10) { continue; }

            $previous_board = $previous_thread = $previous_reply = $category;

            //  make some boards
            foreach (range(1,rand(2,3)) as $b) {
                $board             =  new \Christhompsontldr\Laraboard\Models\Post;
                $board->name       = $faker->sentence(rand(3, 10));
                $board->slug       = str_slug($board->name);
                $board->body       = $faker->sentence;
                $board->type       = 'Board';
                $board->user_id    = \App\Role::where('name', 'admin')->first()->users->random()->first()->id;
                $board->ip         = $faker->ipv4;
                $board->created_at = \Carbon\Carbon::parse($previous_board->created_at)->addMinutes(rand(1,500));
                $board->updated_at = $board->created_at;
                $board->save();
                $board->makeChildOf($category);

                //  1 in 10 chance there are no threads
                if (rand(1,10) == 10) { continue; }

                $previous_board = $previous_thread = $previous_reply = $board;

                //  make some threads
                foreach (range(1,rand(1,20)) as $b) {
                    $found = 0;
                    while($found < 1) {
                        $slug = strtolower((str_random(6)));

                        $found = \Christhompsontldr\Laraboard\Models\Post::whereSlug($slug)->count();

                        if ($found == 0) {
                            $found = 1;
                        }
                    }

                    $rand = (($b == 1) ? rand(1,60) : rand(1,300));

                    $thread             = new \Christhompsontldr\Laraboard\Models\Post;
                    $thread->name       = $faker->sentence(rand(3, 10));
                    $thread->slug       = $slug;
                    $thread->body       = '<p>' . implode('</p><p>', $faker->paragraphs($faker->randomDigitNotNull)) . '</p>';
                    $thread->type       = 'Thread';
                    $thread->user_id    = \App\User::get()->random()->id;
                    $thread->ip         = $faker->ipv4;
                    $thread->created_at = \Carbon\Carbon::parse($previous_thread->created_at)->addMinutes($rand);
                    $thread->updated_at = $thread->created_at;
                    $thread->save();
                    $thread->makeChildOf($board);

                    $previous_thread = $previous_reply = $thread;

                    //  1 in 10 chance there are no replies
                    if (rand(1,10) == 10) { continue; }

                    //  make some replies
                    foreach (range(1,rand(1,30)) as $r) {
                        $found = 0;
                        while($found < 1) {
                            $slug = strtolower((str_random(6)));

                            $found = \Christhompsontldr\Laraboard\Models\Post::whereSlug($slug)->count();

                            if ($found == 0) {
                                $found = 1;
                            }
                        }

                        $reply             = new \Christhompsontldr\Laraboard\Models\Post;
                        $reply->slug       = $slug;
                        $reply->body       = '<p>' . implode('</p><p>', $faker->paragraphs($faker->randomDigitNotNull)) . '</p>';
                        $reply->type       = 'Reply';
                        $reply->user_id    = \App\User::get()->random()->id;
                        $reply->ip         = $faker->ipv4;
                        $reply->created_at = \Carbon\Carbon::parse($previous_reply->created_at)->addMinutes($rand);
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
