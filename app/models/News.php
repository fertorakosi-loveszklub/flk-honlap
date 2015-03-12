<?php

class News extends Eloquent {

    use SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    protected $dates = ['deleted_at'];

    public function author()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    public static function urlFriendlify($string) {
        // Spaces
        $string = preg_replace('/\\s+/', '-', $string);

        // Umlauts and such
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $string = iconv("utf-8","ASCII//TRANSLIT", $string);

        // Ignore everything else
        $string = preg_replace("/[^a-zA-Z0-9\-]/", '', $string);

        return $string;
    }

    public static function urlNormalize($string) {
    // Transliterate
    $string = str_replace(array('&#368;', '&#336;', '&#369;', '&#337;'), array('Ű', 'Ő', 'ű', 'ő'), $string);
    $string = str_replace(array('&Aacute;', '&aacute;', '&Eacute;', '&eacute;', '&Iacute;', '&iacute;', '&Oacute;',
        '&oacute;', '&Ouml;', '&ouml;', '&Uacute;', '&uacute;', '&Uuml;', '&uuml;'),
        array('Á', 'á', 'É', 'e', 'Í', 'í', 'Ó', 'ó', 'Ö', 'ö', 'Ú', 'ú', 'Ü', 'Ű'), $string);

    return $string;
}

}