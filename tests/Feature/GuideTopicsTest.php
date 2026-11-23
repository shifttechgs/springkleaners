<?php

namespace Tests\Feature;

use Tests\TestCase;

/** How-To Guides list: one page, filtered by the reader's situation (?topic=), without needing JavaScript. */
class GuideTopicsTest extends TestCase
{
    public function test_the_guides_page_is_indexable_and_filtered_views_are_not(): void
    {
        $this->get('/blog')->assertOk()->assertSee('content="index, follow"', false);
        $this->get('/blog?topic=moving')->assertOk()->assertSee('content="noindex, follow"', false);
        $this->get('/blog?page=2')->assertOk()->assertSee('content="noindex, follow"', false);
    }

    public function test_every_topic_has_at_least_two_guides_and_every_listed_topic_exists(): void
    {
        $topics = config('blog.topics');
        $counts = [];

        foreach (config('blog.posts') as $post) {
            foreach ($post['topics'] ?? [] as $topic) {
                $this->assertArrayHasKey($topic, $topics, "{$post['slug']} uses unknown topic {$topic}");
                $counts[$topic] = ($counts[$topic] ?? 0) + 1;
            }
        }

        foreach (array_keys($topics) as $key) {
            $this->assertGreaterThanOrEqual(2, $counts[$key] ?? 0, "Topic {$key} has fewer than 2 guides");
        }
    }

    public function test_topic_filter_works_without_javascript(): void
    {
        $html = $this->get('/blog?topic=builders')->assertOk()->getContent();

        // Guides outside the topic are rendered hidden; guides inside it are visible.
        preg_match_all('#<li x-show="[^"]*"(\s+style="display:none")?\s*>\s*<a href="([^"]+)" class="guides-row">#', $html, $rows, PREG_SET_ORDER);
        $visible = collect($rows)->filter(fn ($r) => $r[1] === '')->map(fn ($r) => $r[2])->values();

        $expected = collect(config('blog.posts'))
            ->filter(fn ($p) => in_array('builders', $p['topics'] ?? [], true))
            ->map(fn ($p) => route('blog.show', $p['slug']))->sort()->values();

        $this->assertEquals($expected->all(), $visible->sort()->values()->all());
        $this->assertStringContainsString('aria-current="true"', $html);
    }

    public function test_unknown_or_old_filters_fall_back_to_all_guides(): void
    {
        $this->get('/blog?topic=not-a-topic')->assertOk()->assertSee('All '.count(config('blog.posts')).' guides');
        $this->get('/blog?category=Tips')->assertOk();
        $this->get('/blog?page=999')->assertOk();
    }
}
