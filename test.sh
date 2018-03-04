#curl http://aimei.wangp.org/buyer/login/register --data "name=user4&email=b4@b&password=7e58d63b60197ceb55a1c487989a3720&use_md5=1" -v
#curl http://aimei.wangp.org/buyer/login/show --cookie "PHPSESSID=1nhqkiqpkrkoc3cmk6cnjvbaj7;"

#curl http://218.244.144.114:7171/user/user/login --data "phone=18610455401&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl http://218.244.144.114:7171/user/order/wxPay --data "payment_id=109631" -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/user/easemob/infos --data "usernames=[\"buyer_a9c7a3935a13986f29ba5749bba70a87\",\"user_5d93b863403761da0545ab4df8735423\",\"user_b383944de88cfc0f8a13b9e9d17669e1\"]" -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/user/user/info -b test_cookie -c test_cookie
#curl http://aimei1.wangp.org/user/user/login --data "phone=18610455401&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei1.wangp.org/user/user/uploadHead -F 'avatar=@/Users/wp/Projects/aimei_backend/baidu_jgylogo3.gif;filename=baidu_jgylogo3.gif' -F 'update=1' -b test_cookie  -v --cookie-jar test_cookie

#curl http://aimei1.wangp.org/user/user/login --data "phone=18610455401&password=wp" -b test_cookie -c test_cookie

#curl http://218.244.144.114:7171/user/user/login --data "phone=18610455401&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl http://218.244.144.114:7171/user/order/wxPay --data "payment_id=109631" -b test_cookie -c test_cookie
curl "http://218.244.144.114:7171/user/order/wxNotify?bank_billno=201411116210017769&bank_type=2011&discount=0&fee_type=1&input_charset=UTF-8&notify_id=f6Gs8sGd0Ql9gi58Ued7KPnhNsooamzCas-D0yISRdPcQAunu_5MeOYNn9pII_GSVWfG9yYAwZ1sHZ7MSmUcB3OZvVxYJvNM&out_trade_no=TEST20141111142405100210614&partner=1217927701&product_fee=1&sign=2159E14DF6E1240243D1A34E7B82E159&sign_type=MD5&time_end=20141111142441&total_fee=1&trade_mode=1&trade_state=0&transaction_id=1217927701201411116138917684&transport_fee=0" --data ""

#curl "http://aimei1.wangp.org/admin/index/login" -v --data "name=root&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl "http://aimei1.wangp.org/admin/admin/sug?wd=r"  -b test_cookie -c test_cookie
#echo "\n"
#curl "http://aimei1.wangp.org/admin/order/exportToCsv"  -b test_cookie -c test_cookie
#echo "\n"

#curl "http://aimei1.wangp.org/user/live/forenotice?id=23"  -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei1.wangp.org/user/index/new  -b test_cookie -c test_cookie
#curl http://aimei1.wangp.org/buyer/pack/send --data "id=67&logistic_provider=dsds&logistic_no=sdsadsad&imgs=[\"/public_upload/0/2/3/023666d59c6a05e9e5b509b93f5b42c9.jpeg\"]" -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/buyer/login/show -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/update --data 'id_pics=["1.JPG","2.JPG"]' -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/buyer/login/apply -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/buyer/login/update --data 'id_pics=["1.JPG","3.JPG"]' -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/buyer/login/apply -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/login --data "name=wp&password=wp" -b test_cookie -c test_cookie

#echo "\n"
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/updatePassword --data "old_password=wp1&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/login --data "name=wp&password=wp" -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/order/finishBuy --data 'order_ids=["3"]'  -b test_cookie -c test_cookie
#curl http://aimei.wangp.org/buyer/stock/list?live_id=21   -b test_cookie -c test_cookie 
#curl http://aimei.wangp.org/buyer/stock/show?id=36   -b test_cookie -c test_cookie 

#echo "\n"
#curl http://aimei1.wangp.org/buyer/stock/update --data 'id=1&sku_meta={"颜色":["红色","白色","黄色"],"尺寸":["XL","L","M","S"]}&sku={"红色\tXL":"100","白色\tM":"10","白色\tS":"10","橙色\tL":"10","橙色\tS":"5"}&live_id=21&name=橙色包包1&note=很罕见&imgs=["/winphp/metronic/media/image/dress1.jpg","/public_upload/c/f/5/cf58feb1c64ef4f43b92464bd7ed773f.jpg","/public_upload/b/4/6/b46fbcd330684e37fb838a1a6846331b.jpg"]&pricein=800' -b test_cookie -c test_cookie
#echo "\n"



#curl http://aimei.wangp.org/buyer/order/waitPayList?live_id=1 -b test_cookie  --cookie-jar test_cookie


#curl http://aimei.wangp.org/buyer/login/show -b test_cookie  --cookie-jar test_cookie
#echo "\n"
#echo "\n"
#curl http://218.244.144.114:8000/user/user/login --data "phone=13811311608&password=000000" -b test_cookie -c test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/show -b test_cookie -c test_cookie -v
#echo "\n"
#curl http://aimei.wangp.org/buyer/login/update --data "id_num=88" -b test_cookie -c test_cookie
#curl "http://aimei1.wangp.org/user/buyer/info?id=1" -b test_cookie  --cookie-jar test_cookie
#echo "\n"
#curl http://218.244.144.114:8000/user/user/uploadHead -F 'avatar=@/home/wp/aimei_backend/webroot/upload/files/test1.png;filename=test.png' -F 'update=1' -b test_cookie  -v --cookie-jar test_cookie
#curl http://aimei.wangp.org/buyer/live/list?last_id=25 -b test_cookie  --cookie-jar test_cookie
#curl http://aimei.wangp.org/buyer/stock/create --data 'imgs=[%22%2Fpublic_upload%2F6%2F8%2F9%2F689eb887373230a7a352b40412682127.jpg%22%2C%22%2Fpublic_upload%2Fc%2Ff%2F5%2Fcf58feb1c64ef4f43b92464bd7ed773f.jpg%22%2C%22%2Fpublic_upload%2Fb%2F4%2F6%2Fb46fbcd330684e37fb838a1a6846331b.jpg%22%2C%22%2Fpublic_upload%2F6%2F8%2F9%2F689eb887373230a7a352b40412682127.jpg%22%2C%22%2Fpublic_upload%2Fc%2Ff%2F5%2Fcf58feb1c64ef4f43b92464bd7ed773f.jpg%22%2C%22%2Fpublic_upload%2Fb%2F4%2F6%2Fb46fbcd330684e37fb838a1a6846331b.jpg%22]&live_id=21&name=ceshi&note=100&pricein=100&sku=%7B%22%E9%BB%84%09L%22%3A%225%22%2C%22%E9%BB%84%09M%22%3A%225%22%2C%22%E8%93%9D%09L%22%3A%225%22%7D&sku_meta=%7B%22%E9%A2%9C%E8%89%B2%22%3A[%22%E7%BA%A2%22%2C%22%E9%BB%84%22%2C%22%E8%93%9D%22]%2C%22%E8%A7%84%E6%A0%BC%22%3A[%22L%22%2C%22M%22%2C%22S%22]%7D' -b test_cookie  --cookie-jar test_cookie
#curl http://aimei1.wangp.org/buyer/live/list -b test_cookie
#echo "\n"
#curl http://aimei1.wangp.org/buyer/finance/info -b test_cookie
#echo "\n"
#curl http://aimei.wangp.org/buyer/finance/withdraw --data "no=1234&bank=bank123&type=local&name=wang_peng&password=wp" -b test_cookie
#echo "\n"
#curl http://aimei1.wangp.org/buyer/finance/info -b test_cookie
#curl http://aimei1.wangp.org/user/user/uploadHead --data 'update=1&avatar=data%3Aimage%2Fjpeg%3Bbase64%2C%2F9j%2F4AAQSkZJRgABAQAAAQABAAD%2F2wBDAAEBAQEBAQEBAQEBAQEBAQIBAQEBAQIBAQECAgICAgICAgIDAwQDAwMDAwICAwQDAwQEBAQEAgMFBQQEBQQEBAT%2F2wBDAQEBAQEBAQIBAQIEAwIDBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAT%2FwAARCABLAEsDASIAAhEBAxEB%2F8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL%2F8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4%2BTl5ufo6erx8vP09fb3%2BPn6%2F8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL%2F8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3%2BPn6%2F9oADAMBAAIRAxEAPwD%2B%2BiinvGYwoLbieCcbaWSPZjnOfbFAFiOTfnjGPfNR%2Bf8A7H%2Fj3%2F1qkjk8zPGMY75zUfkf7f8A47%2F9egCSOPZnnOfbFRz%2FAMH4%2FwBKkjk354xj3zUlAFOSTfjjGPfNSf6%2F%2FZ2%2F8Czn%2FwDVUckezHOc%2B2Kk%2FwBR%2Ftbv%2BA4x%2FwDroAsUUUUAcqmoXRVXbSNRIYcKJLXA%2FwDI9WIbu5lJ%2FwCJRqQxgEmW1yev%2FTesLx78QfAnwy8M3Xi74jeMfDXgbwvpduZ9Q8Q%2BK9ZttA0a0TaMtLcTOsajty1eTfCD9qP4EfHn4aat8ZfhT8R9F8UfDHRNQ1Gw1bxmiT6Xodq%2BlFhfSebcxxh4IgrN56ZiZV3I7LWMsVhlW9g5R57c3LfW3fe9jvoZJnWIy95tRw1SWGUlT9pGEvZ88vhhzWtzSs7R3dj6Alu7l8f8SnUBtznL2vf%2FALb1Vk1O7kx%2FxJtQUDv5toev%2Fbb2r5P8J%2Ft%2FfseeKPhxqfxgsPj%2FAPD7T%2FhbZ%2BJ7jwfD488T6wvg7wzrF%2FZqGuItOur%2FAMlLtVLlPMty6M0bqrNtNejfBP8Aap%2FZz%2FaEfVoPgh8a%2Fhr8ULjRVR9Xs%2FBnjCx1zUdKVyyxvcW8crSRq5B2s6hWxxWFPMsvrOKpVISk9veWvpqehjOEOK8upVa%2BPy6vTp0nablSnFQfabcbRfk9bntUuqXLkL%2FYuprjofNtSTnH%2FTepjf3cHTR9RcOe0loNuP8Atv3zXy78Sv22%2FwBkf4OeJJvBvxR%2FaQ%2BDfgTxXAVM%2Fh3xJ8Q9K0nWLQP0M9rJOJYuv8arXwFqf%2FBVa48f%2Ft7%2BFv2Qv2ZNH%2BHvjrwDovh238ffHb49al4ifUvCfhTSfIN7cQ6cbd1gkc28tgovGnaJJL8L5T%2BS9cuLzzKsDKFOvVjzSkoKKfNLmfktfXse9w74WcfcT06%2BJy3Lqiw9GhPEzqzjyUlShbmkpzUYt%2B8oxirylJpRTbP2nF9dngaRqA9zJa%2F%2FAB6o5NSuoyv%2FABJtRbdnjzbRSMY%2F6bV8h6J%2B3v8AsceJ%2FEN14E8IftQ%2FAnWfHs%2FmWWm6BYfE7Rru8vbxEceTEgucSNu%2FgWvn%2BT9qDxp4Z%2BHll8QvGnx9%2FZvsvCNhrt9oHiTxyPHHh%2BDw2LuG2eOCxt71rhYDOlxLE9xER5qfZXVEf7r9izDASXNGrDlX97%2FgnhVOEOKqFSFGvl9dTnblUqU4t325Vy631t3P05XULsj%2FAJA2o4zx%2B9tMZ%2F7%2FAFL9vu%2F%2BgJqB%2FwC29p%2F8fr46%2FZF%2Fax%2BGf7QHhmXTdM%2BOHwh%2BJ3xG0q4mude0n4eeNtK8Q3WnWxlVLaR7e0bcqlGT95sVdz7dzffb7X49QPqwzW9LEUcRD2lBxlHyd%2F1PLzDKczyfFPAZrSnSrx3jOLg%2Fuaufjz%2FwWm%2FYt8Sfte%2Fse%2BKNM%2BFHgDwt4u%2BOfhiSx1XwXLqmn2aeJTZW%2BoW9zqlhpN%2FNj7PNcRRuNu9RLgxnHmZr8J%2F2of20%2Fj5YfsjfAf8A4Jn%2FAAu%2FY%2F8AiN%2BzT46%2BL%2Fh%2FTPhNrcnjCG301tZgha1tNUg0G3jYzyQ3skm24vbkJsgnnGxmZpE%2Fr1%2BNPw48V%2FFb4U%2BKPAHgr4peKfgz4i8QW9vHY%2FEfwXBa3Pifw8IriKaRrT7RE8QaZEaJmKbgkz7cNhh8P%2Fs2f8ErPhP8EPjIf2j%2FAIifEX4qftM%2FtAR2ZsNJ%2BJvxs8RjxDfeGoSrqYtNtVRIbdQsjquwfKJHVdqswPwfEfDuYZhmcq%2BUzlSdWEadWfu8vs7tyUdHPmtppyrbXQ%2FqzwX8auEuD%2BCaGW%2BIFCnjYZbXrYzAYblr%2B0%2BtypQjTlUlGcMM6EZxjPlmpzi4yShaZ%2BCumf8ABPj40fsc%2Fts%2FArxp8Uf2bPiH%2B1b%2ByX8LPhPpvh7wBYfC3SrXxo3hLWf7Itn1G9vPDss0eZ31Z9UupXYFZPtcMiuzxLEnsOt%2Fs2ftw%2Ftcft4eKf2vPgF8Atc%2FYr8LeA%2FgNr3w9%2BHd%2FwCOrWx8G%2BMPiHq9xoesWenXGp6bbNIq%2FwCl39sd029Y49NtfmZhhP6xpCs2wFQGUE5PbOMY9OlTDFt0AZWPAxtHH%2F66unwHl9Kl9Up16kaHtPa2ja%2FMvh963NyrpH9Dhxf0teMswxyz3GZbhamZfUngXUmqkqcqUpOVScsPz%2BxdarzNVKjjZ3b5VLU%2Fj5%2FYb0HWv2Wf2dfjR8O%2Fjz%2FwTu%2FaI8V%2Ftb%2BKdR8QnUPibrXwVHxc8LeLZryKRdNa41qOSaVrVHIEvlCXfl5FZmk5%2FOHwZ%2BwB%2B1Tp37KHxH8E%2FCz4J%2FH%2BX4peIPiHp%2Fib4xafd%2FDjUvhvaeN%2FBVjBcpa6No1zdbTdyQ39wLqew2o8vmwuqSpbtt%2F0GWhiLA%2BUhbGQMA4%2FSqGoq8WmapcWdu9zc29jNNb2lrFGbm7kSORkiTzJI03MwAXfIi5P30%2B8Oet4eYDE0KOHq4iXLThKEdIqVpWvK%2FWXd2117n0eTfTP4qyLN8yznL8po%2B1zDEUcRXjKpVnRcqF%2BSnCm23ClFy5owjL3ZKDT91H8gn7c%2FhbS%2Fj3%2Bxr8Mf2av2av2A%2F2gPgrqnh3xHpOq%2BItT8Ufsu3n9o6fDYWVws0NjeWSzSSXEtxJGJZWZd6CT73mceSfDn9kn4rfDr9on9k%2F4weJP2Rv2gvjV%2BxN8IfA0XhfRfhjN4CWDx74P162tbgarqmr%2BDrqdfMlu9WaTVFuIw6zw3FqVlMluIB73Y%2FtS%2Ftg%2BJtL1LV7X4lfGG5nF42tyalPqfiKxS002Tw94q1W2TyrG7bTIZfs2jTXb732LLpsXySwRXvm%2FsD%2FwUF%2BJ%2FjzwT4%2B0rw14f8bal4a%2F4SL4MahrHhq30Lx9rfgae1uNN1zRpLnUbtLeRbS4aFZEiRHuNL82K8uIPtbyyWcVb1%2BAcHiMasZXrP2kPZ8vKoxj%2B7d4xttZ%2FaXp2PKyj6WnEWScOVOFsvyuDwlX637R1K1apUcsXFQqTjUbU4VIR92lNO8U5K0uZn57eGvgH%2B0t%2B1v%2FAMFTfgl%2B1h4C%2FZu8VfsefAH4F6TBpF94h8Z6RY%2BC%2FHnxNt4jfNPbXOl20rny7hbr7H5U27ZbK7797JEn9PvIABjZiBgkHrXwv%2FwTv8UeKvF%2F7NXh3WvF3iK78V6veanPqD69qvja88b63e%2FborbU9l41xPc%2FY%2FJ%2B2eTDYpdXHlW8Nvvl81pa%2B7QSBjaWx33YzX02UZRSyh1nCUpSqy55OXeyXupaJWXQ%2FC%2FETxGx3iBLLKeJw1Ohh8Bh44ajCHNJ%2BzUpTbqTqOU6k5SnJuTdle0VFaDI5yEQFOQgyN3%2FANarEcnmbvl24x3znrVIgHqKs%2Bf%2FALH%2FAI9%2F9avYPzssVXg%2Fj%2FD%2BtH%2Bo%2FwBrd%2FwHGP8A9dWKACqjTqw2tGWB6g9P5Ukce%2FPOMe2aJE2Fec7s84xjGKAPGx%2Bzr8AbiK2iPwS%2BEwjsNPh0m0V%2Fh3pMot7WD%2FU26Zg%2BWOPHyp%2FDXpUvh7RtSmnub3TNPuLiXTZNImuJ7GKWeaznbdNau7Lu8qTYm6P7p2jjit7%2FAF%2F%2Bzt%2F4FnP%2FAOqrFAGTpWladoWm6do2kafaaXpOk2MOmaXpthbra2Wn29vGkUMEMS%2FKqIkaoqKMKqCtamFFYgsqsVPyllztp9ABRRRQAUUUUAFFFFABRRRQAUUUUAf%2F2Q%3D%3D'  -b test_cookie  --cookie-jar test_cookie
