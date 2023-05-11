<?php

namespace App\Controllers;

class BoardController extends BaseController
{
    private $boardService;
    private $boardWriteService;

    public function __construct()
    {
        $this->boardService = service('BoardService');
        $this->boardWriteService = service('BoardWriteService');
    }
    public function lists($brdKey = '')
    {
        $view = array();
        $view['view'] = array();

        $view['view']['list'] = $this->boardService->_get_list($brdKey);
        return view('board/list',$view);
    }

    public function post($post_id = 0)
    {
        try {
            $view = $this->boardService->post($post_id);
            return view('board/post',$view);
        } catch (\Exception $e) {
            alert($e->getMessage());
        }

    }


    /**
     * 게시물 작성 페이지입니다
     */
    public function write($brdKey = '')
    {

        if (empty($brdKey)) {
            //오류 익셉션 추가하기
            return;
        }

        $board_id = $this->boardService->itemKey('brd_id', $brdKey);
        if (empty($board_id)) {
            //오류 익셉션 추가하기
            return;
        }
        $board = $this->boardService->itemAll($board_id);

        $board['is_use_captcha'] = false;

        if( check_use_captcha($board) ){
            $board['is_use_captcha'] = true;
        }

        $alertmessage = service('MemberService')->isMember()
            ? '회원님은 글을 작성할 수 있는 권한이 없습니다'
            : '비회원은 글을 작성할 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

        $check = array(
            'group_id' => val('bgr_id', $board),
            'board_id' => val('brd_id', $board),
        );
        /*
        $this->accesslevel->check(
            val('access_write', $board),
            val('access_write_level', $board),
            val('access_write_group', $board),
            $alertmessage,
            $check
        );
*/
        $view = $this->boardWriteService->_write_common($board);
        helper('dhtml_editor');

        if (! $view['valid']){
            return view('board/write',$view);
        } else {
            if(! $view['redirecturl']) {
                return view('board/write',$view);
            } else {
                return redirect($view['redirecturl']);
            }
        }
    }
}

