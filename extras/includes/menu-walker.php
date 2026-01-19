<?php
/**
 * Walker that applies *custom* menu item classes to <a> instead of <li>.
 * Structural/core classes remain on <li> (e.g., current-menu-item).
 *
 * @package sapling-theme
 * @author theowolff
 */

defined('ABSPATH') || exit;

if (!class_exists('Sapling_Anchor_Classes_Walker')) {
  class Sapling_Anchor_Classes_Walker extends Walker_Nav_Menu {

    /**
     * Start the element output.
     *
     * @param string  $output Used to append additional content.
     * @param WP_Post $item   Menu item.
     * @param int     $depth  Depth of menu item.
     * @param array   $args   wp_nav_menu() args.
     * @param int     $id     Item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
      $indent = ($depth) ? str_repeat("\t", $depth) : '';

      $custom_classes = get_post_meta($item->ID, '_menu_item_classes', true);
      $custom_classes = is_array($custom_classes) ? array_filter($custom_classes) : [];

      $anchor_classes = array_filter($custom_classes, fn($c) => strpos($c, 'hide-') !== 0);

      $li_classes = is_array($item->classes) ? array_filter($item->classes) : [];
      if (!empty($anchor_classes)) {
        $li_classes = array_diff($li_classes, $anchor_classes);
      }

      // Standard li classes cleanup.
      $class_names = join(' ', apply_filters('nav_menu_css_class', array_unique($li_classes), $item, $args, $depth));
      $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

      // ID attribute for <li>.
      $li_id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
      $li_id = $li_id ? ' id="' . esc_attr($li_id) . '"' : '';

      // Open <li>.
      $output .= $indent . '<li' . $li_id . $class_names . '>';

      // 3) Build <a> attributes.
      $atts = [];
      $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
      $atts['target'] = !empty($item->target) ? $item->target : '';
      $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
      $atts['href']   = !empty($item->url) ? $item->url : '';

      $link_classes = [];
      if (!empty($args->link_class)) {
        $link_classes[] = $args->link_class;
      }
      if (!empty($anchor_classes)) {
        $link_classes = array_merge($link_classes, $anchor_classes);
      }
      $link_classes = array_unique(array_filter(array_map('trim', $link_classes)));
      if (!empty($link_classes)) {
        $atts['class'] = implode(' ', $link_classes);
      }

      // Allow filters to adjust <a> attributes.
      $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

      // Build <a>.
      $attributes = '';
      foreach ($atts as $attr => $value) {
        if (empty($value)) {
          continue;
        }
        $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }

      // Link text/title.
      $title = apply_filters('the_title', $item->title, $item->ID);
      $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

      $item_output  = $args->before ?? '';
      $item_output .= '<a' . $attributes . '>';
      $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
      $item_output .= '</a>';
      $item_output .= $args->after ?? '';

      $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
  }
}
