<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Acme Themes
 * @subpackage AcmePhoto
 */
global $acmephoto_customizer_all_values;
$no_image ='';
if( !has_post_thumbnail() ){
    $no_image = 'acme-no-image';
}
$acmephoto_blog_enable_gap = $acmephoto_customizer_all_values['acmephoto-blog-enable-gap'];
$acmephoto_blog_show_title = $acmephoto_customizer_all_values['acmephoto-blog-show-title'];
$acmephoto_blog_show_cats = $acmephoto_customizer_all_values['acmephoto-blog-show-cats'];
$acmephoto_blog_show_comments = $acmephoto_customizer_all_values['acmephoto-blog-show-comments'];
$acmephoto_blog_show_date = $acmephoto_customizer_all_values['acmephoto-blog-show-date'];
$acmephoto_blog_show_author = $acmephoto_customizer_all_values['acmephoto-blog-show-author'];
$gab = "acme-col-3 article-ap masonry-post ";
if( 1!= $acmephoto_blog_enable_gap ){
    $gab .= ' no-gab';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($gab); ?>>
    <div class="inner-wrapper <?php echo $no_image?>">
        <!--post thumbnal options-->
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>" class="thumb-holder-link">
                <?php
                if( !has_post_thumbnail( ) ){
                    ?>
                    <table class="no-image-table-masonry">
                        <tbody>
                        <tr>
                            <td>
                                <?php the_title();?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php
                }
                else{
                    the_post_thumbnail('large');
                }
                ?>
            </a>
            <div class="at-content-wrapper">
                <div class="at-overlay">
                    <div class="acme-col-1">
                        <div class="entry-title">
                            <?php
                            if( 1 == $acmephoto_blog_show_title ){ ?>
                                <header class="entry-header">
                                    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                                </header><!-- .entry-header -->
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="acme-col-2">
                        <div class="entry-data">
                            <?php if ( 'post' === get_post_type() ) : ?>
                                <div class="entry-meta">
                                    <?php acmephoto_posted_on( $acmephoto_blog_show_date, $acmephoto_blog_show_author ); ?>
                                </div><!-- .entry-meta -->
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="acme-col-2 float-right">
                        <footer class="entry-footer">
                            <?php acmephoto_entry_footer($acmephoto_blog_show_cats, 0, $acmephoto_blog_show_comments, 0); ?>
                        </footer><!-- .entry-footer -->
                    </div>
                </div>
            </div>
        </div><!-- .post-thumb-->
    </div>
</article><!-- #post-## -->