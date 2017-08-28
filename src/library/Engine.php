<?php
namespace Library;
/**
 * @author   haicheng
 * @datetime 17/2/20 下午5:33
 */
include "sms_sdk/TopSdk.php";

class Engine {

    /**
     * 电话
     */
    private $_numbers = [
        '15726659029',
    ];

    private $_line = "curl 'http://zzfws.bjjs.gov.cn/enroll/dyn/enroll/viewEnrollHomePager.json' -H 'Pragma: no-cache' -H 'Origin: http://zzfws.bjjs.gov.cn' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.6,en;q=0.4,ja;q=0.2' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36' -H 'Content-Type: application/json;charset=UTF-8' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Cache-Control: no-cache' -H 'X-Requested-With: XMLHttpRequest' -H 'Cookie: JSESSIONID=70BDB0B4E430B699E56C2A06DE6D1E47; _gscu_1677760547=86449979clw4s413; _gscbrs_1677760547=1; Hm_lvt_9ac0f18d7ef56c69aaf41ca783fcb10c=1486449980; Hm_lpvt_9ac0f18d7ef56c69aaf41ca783fcb10c=1487570629; session_id=bcd9ce88-103b-402b-b19a-c5980f25955b' -H 'Connection: keep-alive' -H 'Referer: http://zzfws.bjjs.gov.cn/enroll/home.jsp' --data-binary '{\"currPage\":1,\"pageJSMethod\":\"goToPage\"}' --compressed";

    private $_result_of_curl_template = '<div class="spacing"></div><center><img src="http://zzfws.bjjs.gov.cn:80/enroll/resources/enroll/CSS/images/img_dailog_enrollnone.jpg"/></center><hr>';

    /**
     * start
     * 任务开始
     */
    public function start() {
        $result_of_command = exec($this->_line);
        $result_data       = json_decode($result_of_command, true);
        if (isset($result_data['flag']) && isset($result_data['data'])) {
            if (1 !== intval($result_data['flag'])) {
                //报警
                $this->sendErrorMessage();
                exit();
            }
            $have_message = $this->_result_of_curl_template !== $result_data['data'];
            if ($have_message) {

                //通知
                $this->sendSuccessMessage();
                exit();
            } else {
                $this->sendTaskMessage();
            }
        } else {
            //报警
            $this->sendErrorMessage();
            exit();
        }
    }

    /**
     * sendTaskMessage
     * 任务短信
     */
    public function sendTaskMessage() {
        foreach ($this->_numbers as $mobile) {
            $this->_sendMsg($mobile, "SMS_48095032");
        }
    }

    /**
     * sendTaskMessage
     * 查询有结果短信
     */
    public function sendSuccessMessage() {
        foreach ($this->_numbers as $mobile) {
            $this->_sendMsg($mobile, "SMS_48205001");
        }
    }

    /**
     * sendTaskMessage
     * 出错短信
     */
    public function sendErrorMessage() {
        foreach ($this->_numbers as $mobile) {
            $this->_sendMsg($mobile, "SMS_48185005");
        }
    }

    private function _sendMsg($mobile, $template) {
        $c            = new \TopClient();
        $c->appkey    = getenv('appkey');
        $c->secretKey = getenv('secretKey');

        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("海程提示");
        $req->setSmsParam("{\"time\":\"" . date('m-d H:i', time()) . "\"}");
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode($template);

        return $c->execute($req);
    }
}
