<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public string $uriSegmentAdmin  = 'admin';
    public string $uriSegmentBoard = 'board';
    public string $uriSegmentWrite = 'write';
    public string $uriSegmentReply = 'reply';
    public string $uriSegmentModify = 'modify';
    public string $uriSegmentRss = 'rss';
    public string $uriSegmentGroup = 'group';
    public string $uriSegmentDocument = 'document';
    public string $uriSegmentFaq = 'faq';
    public string $uriSegmentPost = 'post';
    public string $uriSegmentPostType = 'A';
    public string $uriSegmentCmallItem = 'item';

    public string $uploadsDir = 'post';

}