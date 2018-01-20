<?php
//Function to show related tags on article page
class Related_Topics_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct('Related_Topics_widget', // Base ID
            'Related Topics', // Name
            array(
            'description' => __('Displays related topics')
        ));
    }
    function update($new_instance, $old_instance)
    {
        $instance          = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        //$instance['post_type'] = strip_tags($new_instance['post_type']);
        //$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
        return $instance;
    }
    function form($instance)
    {
        if ($instance) {
            $title = esc_attr($instance['title']);
        } else {
            $title = '';
        }
?>
       <p>
            <label for="<?php
        echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sim_most_viewed'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php
    }
    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        $this->relatedtopics();
        echo $after_widget;
    }
    
    function relatedtopics() //html
    {
        if (is_single()) {
            $postid          = get_the_ID();
            $category_detail = get_the_category($postid);
            $postslug        = $category_detail[0]->slug;
            $catm            = get_category_by_slug($postslug);
            $catmID          = $catm->term_id;
            $parent_cat      = get_the_category($postid);
            $cat_id          = $parent_cat[0]->category_parent;
            $categorieschild = get_categories('child_of=' . $cat_id);
            if (is_array($categorieschild)) {
                echo "<ul class='td-category-custom'>";
                foreach ($categorieschild as $categorychild) {
                    $topicvar;
                    echo "<li class='entry-category'><a href=" . get_category_link($categorychild->cat_ID) . ">" . $categorychild->cat_name . "</a><span>,</span></li>";
                }
                echo "</ul>";
            }
        }
    }
} //end class
register_widget('Related_Topics_Widget');