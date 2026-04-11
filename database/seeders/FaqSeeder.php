<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('enums.faqs') as $key => $setting) {
            Faq::create([
                'slug' => $key,
                'content' => json_encode([
                     'faqs' => [
                            [
                                'ques' => 'How do I track my order?',
                                'answer' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima expedita nesciunt explicabo velit sequi facere maiores illo dolores in veritatis doloribus delectus non, amet temporibus, recusandae porro eum ullam possimus.',
                            ],
                            [
                                'ques' => 'Do you offer international shipping?',
                                'answer' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima expedita nesciunt explicabo velit sequi facere maiores illo dolores in veritatis doloribus delectus non, amet temporibus, recusandae porro eum ullam possimus.',
                            ],
                            [
                                'ques' => 'How can I contact customer support?',
                                'answer' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima expedita nesciunt explicabo velit sequi facere maiores illo dolores in veritatis doloribus delectus non, amet temporibus, recusandae porro eum ullam possimus.',
                            ],
                            [
                                'ques' => 'Can I change my order after it has been placed?',
                                'answer' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minima expedita nesciunt explicabo velit sequi facere maiores illo dolores in veritatis doloribus delectus non, amet temporibus, recusandae porro eum ullam possimus.',
                            ],

                        ]

                    ])

            ]);
        }
    }
}
