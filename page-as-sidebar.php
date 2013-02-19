<?php
/*
Plugin Name: Page as Sidebar 
Plugin URI: #
Description: Use a page's content on your sidebar
Author: Joe Cyboski
Version: 1.0
Author URI: http://cybo42.com
 */


class Widget_PageAsSidebar extends WP_Widget
{

    function Widget_PageAsSidebar()
    {
        $widget_options = array(
            'classname' => 'widget-page-as-sidebar',
            'description' => "Use a page for sidebar content",
        );

        $id_base = 'widget-page-as-sidebar';

        $control_options = array(
            'height' => 300, 'width' => 250, 'id_base' => $id_base
        );

        $this->WP_Widget($id_base, 'Page as Sidebar', $widget_options, $control_options);

    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        $title = ($instance['title']) ? $instance['title'] : 'Page As Sidebar';
        $pageId = ($instance['pageId']) ? $instance['pageId'] : '';


        if ($pageId > 0) {
            $page = get_post($pageId);
            $body = $page->post_content;
        } else {
            $body = "Please select a page to use";
        }


        echo $before_widget;

        if ($instance['title']) {
            echo $before_title;
            echo esc_html($instance['title']);
            echo $after_title;

        }

        echo $body;
        echo $after_widget;


    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_title($new_instance['title']);
        $instance['pageId'] = $new_instance['pageId'];
        return $instance;
    }

    function form($instance)
    {
        ?>
        <label for"<?php echo $this->get_field_id('title');?>">
        <?php _e('Title: '); ?>
        <input id="<?php echo $this->get_field_id('title');?>"
               name="<?php echo $this->get_field_name('title');?>"
               type="text"
               value="<?php echo esc_attr($instance['title']);?>"/>
        </label>
        <label for"<?php echo $this->get_field_id('pageId');?>">
        <select id="<?php echo $this->get_field_id('pageId');?>"
                name="<?php echo $this->get_field_name('pageId');?>" >
            <option value="-1">---- None ---</option>
            <?php
            $pages = get_pages();
            foreach ($pages as $page) {
                $option = '<option value="' . $page->ID . '"';
                if ($instance['pageId'] == $page->ID) {
                    $option .= ' selected="true" ';
                }
                $option .= '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            ?>

        </select>
        </label>
<?php
    }
}


function pageassidebar_load()
{
    register_widget('Widget_PageAsSidebar');
}

add_action('widgets_init', 'pageassidebar_load');

