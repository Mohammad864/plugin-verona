<?php

namespace RabbitExamplePlugin\Admin;

use WP_List_Table;

class BookInfoTable extends WP_List_Table
{
    public function prepare_items()
    {
        // Logic to prepare data for displaying in table
    }

    public function display_table()
    {
        $this->prepare_items();
        $this->display();
    }
}
