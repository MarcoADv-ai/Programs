;usar esa json para leer la caga de respuestas de la  api de keyauth
#Include %A_ScriptDir%\Lib\JSON.ahk
#NoEnv
#Warn
SetWorkingDir %A_ScriptDir%
SetBatchLines -1

;datos keyauth
global keyauth_name := "test"
global keyauth_ownerid := "7qKDWi4hyd"
global keyauth_secret := "a7ee9eff0d7030f6a81811187aeabdcbf790bd9316d3468bb859f6b58c783589"
global keyauth_version := "1.0"
global session_id := ""
global enckey := ""

;gui login
Gui, Login:New, +AlwaysOnTop
Gui, Login:Add, Text, x10 y10, Usuario:
Gui, Login:Add, Edit, x10 y30 w200 vUsername
Gui, Login:Add, Text, x10 y60, Contraseña:
Gui, Login:Add, Edit, x10 y80 w200 vPassword +Password
Gui, Login:Add, Button, x60 y110 w100 gLoginButton, Login
Gui, Login:Show, w220 h140, Login

return

ShowMessage(title, text, owner := "") {
    if (owner) {
        Gui, %owner%:+Disabled  ; Deshabilitar la ventana principal
    }
    MsgBox, 4096, %title%, %text%  ; 4096 = Always on top
    if (owner) {
        Gui, %owner%:-Disabled  ; Rehabilitar la ventana principal
    }
}

LoginButton:
Gui, Login:Submit, NoHide
if (Username = "" or Password = "") {
    ShowMessage("Error", "Por favor, ingrese usuario y contraseña.", "Login")
    return
}

hwid := GetHardwareID()
allowed_hwid := "AA20220148037088" ; Reemplaza con el hardware ID autorizado

if (hwid != allowed_hwid) {
    ShowMessage("Error", "Esta PC no está autorizada para usar este software.", "Login")
    ExitApp
}

if (Init()) {
    if (Login(Username, Password)) {
        ShowMessage("Éxito", "Login exitoso!", "Login")
        Gui, Login:Destroy
        GoSub, ShowMainGui
    } else {
        ShowMessage("Error", "Login fallido. Verifique sus credenciales.", "Login")
    }
} else {
    ShowMessage("Error", "No se pudo inicializar KeyAuth.", "Login")
}
return

Init() {
    url := "https://keyauth.win/api/1.2/"
    data := "type=init&name=" . keyauth_name . "&ownerid=" . keyauth_ownerid . "&ver=" . keyauth_version

    response := SendHTTPRequest(url, data)
    if (response = "") {
        ShowMessage("Error", "No se pudo conectar con el servidor.", "Login")
        return false
    }

    parsed := JSON.Load(response)
    if (parsed["success"]) {
        session_id := parsed["sessionid"]
        enckey := parsed["enckey"]
        return true
    } else {
        ShowMessage("Error", parsed["message"], "Login")
        return false
    }
}
GetHardwareID() {
    try {
        for objItem in ComObjGet("winmgmts:\\.\root\cimv2").ExecQuery("Select * from Win32_DiskDrive") {
            return objItem.SerialNumber  ; Devuelve solo el primer HWID encontrado
        }
    }
    return ""
}



Login(user, pass) {
    url := "https://keyauth.win/api/1.2/"
    data := "type=login&username=" . user . "&pass=" . pass . "&sessionid=" . session_id . "&name=" . keyauth_name . "&ownerid=" . keyauth_ownerid

    response := SendHTTPRequest(url, data)
    if (response = "") {
        ShowMessage("Error", "No se pudo conectar con el servidor.", "Login")
        return false
    }

    parsed := JSON.Load(response)
    if (parsed["success"]) {
        return true
    } else {
        ShowMessage("Error", parsed["message"], "Login")
        return false
    }
}

SendHTTPRequest(url, data) {
    try {
        WebRequest := ComObjCreate("MSXML2.XMLHTTP.6.0")
        WebRequest.Open("POST", url, false)
        WebRequest.SetRequestHeader("Content-Type", "application/x-www-form-urlencoded")
        WebRequest.Send(data)
        
        response := WebRequest.ResponseText
        WebRequest := ""
        return response
    } catch e {
        ShowMessage("Error", "Error en la conexión: " . e.message, "Login")
        return ""
    }
}

ShowMainGui:
;ACA PEGA TU AHK
Gui, Main:New, +AlwaysOnTop
Gui, Main:Add, Text,, Main script c:
Gui, Main:Add, Button, gStartBot, Iniciar el mejor hack del mundo
Gui, Main:Show
return

StartBot:
;nohacer nadaxdxd
return

GuiClose:
ExitApp

