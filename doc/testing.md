Testing ( Selenium ) 
====================

這邊的 Testing 主要著重在 Selenium Testing (on Product/ News)，主要在測試 Phifty 的環境是否正確建構，同時測試相關的功能是否如期運作。

Enviroment Settings
===================

- 先準備下面三個檔案

    $ cp config/application.dev.yml config/application.yml
		$ cp config/database.default.yml config/database.yml
		$ cp config/framework.testing.yml config/framework.yml

- 設定 database.yml

通常是把 data_sources/default 更改成已經設定好的資料庫資訊，舉例如下（其他的就不用修改了）：
	
		data_sources:
  		default:
    		dsn: 'mysql:host=localhost;dbname=phifty'
    		user: testing
    		pass: testing

- 設定 framework.yml

這個檔案已經設定成目前測試「涵蓋的所有功能」。有可能 Phifty 未來會提供更多的功能，但是當開啟那些功能的時候會影響到目前的測試，進而造成測試失敗，因此建議不要修改，用預設的就好。(除非你要幫忙寫新的測試 XD) 

- 設定 testing.yml

這邊通常有兩個地方可以設定，一個就是要測試用的 ***Browser*** ( 可以換成 firefox / chrome … )，另一個就是 ***UploadFilePath*** ( 可以 換成你想測試用的上傳圖檔的路徑)

不過這邊要注意的是，你的環境內要有相關的 BrowserWebdriver 來搭配你的 Browser 選項（後面會解釋）。

接下來就是一連串建置環境的過程，請參考***doc/getting_started/setup.md (L: 209 - 228)***來建置環境。

Install
=======

我知道你剛經歷過了建置 Phifty 主程式環境（還有 Testing 相關的設定）的惡夢 … 但是為了執行 Selenium 你還需要安裝一些東西，You are almost there ! 撐住。

這些必要的東西雖然都寫成 script 來幫你安裝，不過還是需要你「手動」更新一下檔案的路徑（因為 Browser 更新的速度之快，Selenium 需要支援相關的操作，再加上測試的 Browser/platform 因人而異，所以這一部份真的是需要自己設定）

- 先開啟 script
		$ vim scripts/install-selenium

- 改第 26 行

SeleniumDownloadLink=""

這邊你要先去 http://code.google.com/p/selenium/downloads/list 這個地方尋找最新版的 ***selenium-server-standalone-{version}.jar*** 這個檔案，把最新版的 .jar 檔路徑複製到那個變數內。

- 再改第 29 行 (如果你不是要測試 chrome 就不用特別留意這邊了 )

ChromeDownloadLink="" 

這邊你要去 http://code.google.com/p/chromedriver/downloads/list 這個地方尋找最新版的 ***chromedriver_{platform}_{version}.zip***這個檔案，把最新版的 .zip 檔路徑複製到那個變數內。

P.S. ***Firefox*** 的 webdriver 是附在 Selenium-server-standalone.jar 內的，所以可以不用特別去安裝這一個。

- 全部搞定了嗎？開始裝吧

		$ ./scripts/install-selenium

How to Run
==========

現在假設你上面的所有操作都沒有遇到問題，那很好，我們已經可以開始測試了，如果你還沒忘記的話，在剛剛執行完 install-selenium 腳本的時候，應該有看到一段文字大概是像這樣：

java -jar …selenium-server.jar \ 
	-Dwebdriver.chrome.driver= …chromedriver \
  -browserSessionReuse

請開一個新的 shell tab 讓它執行這一段訊息，這樣就可以成功的建立起 Selenium standalone server 了（不要關），接下來就是執行測試的時候了。

How to run tests
================

在根目錄輸入以下指令

$ phpunit tests/functionl

你就會看到執行預設好的測試，如果測試不成功就會有相關的錯誤訊息出現，再把錯誤訊息丟出來大家討論看看吧！（本文完）

疑難排解
=======

- Change the locale into `en` first, because I have to use CSS selector to select elements easily

- USE phpunit/PHPUnit_Selenium >= 1.2.8 to solve screenshot, and @depends problems
		pear install -a phpunit/PHPUnit_Selenium (當你在安裝 Selenium Standalone Server 的時候就會自動安裝了)

Known Problems
==============

Chrome will crashed when clicking on input[type=submit], this problem happens because Chrome will calculate the ***CORRECT*** position of the element and click with the coordinates !!! In our input[type=submit] example, we can find that <span> wraps input[type=submit] and make it a little bit bottom-right. This will make Chrome click on ***CLOSE*** <span>. ( see REF 3, 4 )

After testing, if we set the margin-right bigger so that there is no overlapping between CLOSE <span> and input[type=submit], then this issue will be solved .... 

SOLUTION : Maybe try to inject CSS on this test file ?

REF
===

- TinyMCE bug on FF http://code.google.com/p/selenium/issues/detail?id=3430, http://code.google.com/p/selenium/issues/detail?id=3569

- FIX @depends problem with this commit first ( This has been fixed on PHPUnit_Selenium >= 1.2.8 )
	https://github.com/giorgiosironi/phpunit-selenium/commit/112a68e42832935914f47dacaac0544c8f87886a

- http://code.google.com/p/selenium/issues/detail?id=2766

- https://groups.google.com/forum/?fromgroups#!topic/selenium-developer-activity/-MiCZEWNZ_s%5B1-25%5D

OTHERS
======

- Enable Native events on FF seems no help about TinyMCE problems, keep following this discussion list



Selenium and Jenkins CI Environment
----------------------
Install firefox and xvfb

    apt-get install firefox firefox-locale-zh-hant xvfb



