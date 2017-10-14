lua_ret_code = 0;


-- use absolute path
function file_exists(path)
    local attr = lighty.stat("/home/bae/app/"..path)
    if (attr) then
        return true
    else
        return false
    end
end

if (string.find(lighty.env["uri.path"], "^/$") and file_exists("index.html")) then
         lighty.env["uri.path"] = "index.html"

elseif (string.find(lighty.env["uri.path"], "^/$") and file_exists("index.php")) then
         lighty.env["uri.path"] = "index.php"

end

-- redirect host
for cycle = 1,1 do
    preg_match_ret_code, preg_match_substring = preg_match([[^dgzyyy.com$]], lighty.request["Host"]);
    if (1 == preg_match_ret_code) then
            lighty.header["Location"] = "http://www.dgzyyy.com"..lighty.env["request.uri"]
            lua_ret_code = 301
            break;
    end
    break;
end
lighty.errordoc["404"] = "/error/404.html"
lighty.expire[".jpg"] = "modify 10 years"
lighty.expire[".swf"] = "modify 10 years"
lighty.expire[".png"] = "modify 10 years"
lighty.expire[".gif"] = "modify 10 years"
lighty.expire[".JPG"] = "modify 10 years"
lighty.expire[".ico"] = "modify 10 years"

return lua_ret_code;

