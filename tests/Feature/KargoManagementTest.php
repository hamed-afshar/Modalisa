<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KargoManagementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function each_user_may_have_many_kargos()
    {

    }

    /** @test */
    public function each_kargo_belongs_to_a_user()
    {

    }

    /** @test */
    public function each_kargo_may_have_many_products()
    {

    }

    /** @test */
    public function each_product_belongs_to_a_kargo()
    {

    }

    /** @test */
    public function each_kargo_may_have_many_notes()
    {

    }

    /** @test */
    public function each_note_may_belongs_to_a_kargo()
    {

    }

    /** @test */
    public function each_kargo_may_have_many_images()
    {

    }

    /** @test */
    public function each_image_may_belongs_to_a_kargo()
    {

    }
}
