local renderId = "{{RENDERID}}"

game.Players:CreateLocalPlayer(0)
game.Players.Player.CharacterAppearance = "{{APP}}"
game.Players.Player:LoadCharacter()

local thumb = game:GetService("ThumbnailGenerator")
local result = thumb:Click("PNG", 500, 500, true)

local WEBHOOK_URL = "http://torium.fun/render/logs.php"

local function escapeStr(s)
    s = s:gsub('\\', '\\\\')
    s = s:gsub('"', '\\"')
    s = s:gsub('\n', '\\n')
    s = s:gsub('\r', '\\r')
    s = s:gsub('\t', '\\t')
    return s
end

local function toJson(obj)
    local t = type(obj)
    if t == "number" or t == "boolean" then
        return tostring(obj)
    elseif t == "string" then
        return '"' .. escapeStr(obj) .. '"'
    elseif t == "table" then
        local isArray = true
        local maxIndex = 0
        for k,v in pairs(obj) do
            if type(k) ~= "number" then
                isArray = false
            else
                if k > maxIndex then maxIndex = k end
            end
        end

        local result = {}
        if isArray then
            for i = 1, maxIndex do
                table.insert(result, toJson(obj[i]))
            end
            return "[" .. table.concat(result, ",") .. "]"
        else
            for k,v in pairs(obj) do
                table.insert(result, '"' .. escapeStr(k) .. '":' .. toJson(v))
            end
            return "{" .. table.concat(result, ",") .. "}"
        end
    else
        return 'null'
    end
end

local payload = {
    renderid = renderId,
    render = result
}

game:httpPost(
    WEBHOOK_URL,
    toJson(payload)
)
