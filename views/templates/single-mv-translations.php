<?php get_header(); ?>

<div class="wrap">
    <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
    
            <?php

            global $wpdb;
            $q = $wpdb->prepare(
                "SELECT meta_value
                FROM $wpdb->posts AS p
                INNER JOIN $wpdb->translationmeta AS tm
                ON p.ID = tm.translation_id
                WHERE p.ID = %d",
                get_the_ID()  
            );
            $results = $wpdb->get_results( $q, ARRAY_A );
            $has_transliteration = $results[0]['meta_value'] == "Yes" ? "has-transliteration" : "";
            $video_url = esc_url($results[1]['meta_value']);
            $singers = get_the_terms( $post->ID, 'singers' );

            var_dump($results);

            while( have_posts() ): 
                the_post();

            ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( $has_transliteration ); ?>>
                        <div class="translation-item">                   
                            <div class="content">
                                <h1><?php the_title(); ?></h1>
                                <div class="meta">
                                    <span class="singer"><strong><?php _e( 'Singer', 'mv-translations' ); ?>:</strong>
                                        <?php foreach( $singers as $singer ): ?>
                                            <a href="<?php echo esc_url(get_term_link( $singer )); ?>"><?php esc_html($singer->name); ?></a>
                                        <?php endforeach; ?>
                                    </span>
                                    <span class="author"><strong><?php _e( 'Author', 'mv-translations' ); ?>: </strong>
                                        <?php the_author_posts_link(); ?>
                                    </span>
                                    <span class="the-date"><strong><?php _e( 'Published on', 'mv-translations' ); ?>: </strong>
                                        <?php the_date(); ?>
                                    </span>                            
                                </div>
                                <div class="content">
                                    <?php the_content(); ?>                        
                                </div>
                                <?php do_action('mvt_before_video'); ?>
                                <?php if(!empty($video_url)): ?>
                                    <div class="video">
                                        <?php
                                        global $wp_embed;
                                        $video_embed = $wp_embed->run_shortcode('[embed width="560" height="315"]' . $video_url . '[/embed]');

                                        //Example for how to use filters
                                        // echo apply_filters('mvt_video', $video_embed, $post_title);

                                        echo $video_embed;
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </main>
        </div>
    </div>
<?php
get_footer();