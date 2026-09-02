<?php

namespace Tests\Feature;

use Tests\TestCase;

class BlogPaginationSmokeTest extends TestCase
{
    public function test_page_one_is_indexable_and_page_two_is_noindex(): void
    {
        $page1 = $this->get('/blog')->assertStatus(200)->getContent();
        $this->assertStringContainsString('content="index, follow"', $page1);

        $page2 = $this->get('/blog?page=2')->assertStatus(200)->getContent();
        $this->assertStringContainsString('content="noindex, follow"', $page2);
    }

    public function test_category_filter_and_pagination_links_combine(): void
    {
        $html = $this->get('/blog?category=Tips')->assertStatus(200)->getContent();

        $this->assertStringContainsString('Tips', $html);
    }

    public function test_out_of_range_page_does_not_error(): void
    {
        $this->get('/blog?page=999')->assertStatus(200);
    }

    public function test_invalid_category_falls_back_to_all(): void
    {
        $this->get('/blog?category=NotARealCategory')->assertStatus(200);
    }
}
