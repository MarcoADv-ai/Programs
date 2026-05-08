#NoTrayIcon

pastebinURL := "https://pastebin.com/raw/tC0Uh0ut"

Gui, Color, CFCFFF
Gui, Add, Text, x95 y10 w200 h30, Ingresa una key valida:
Gui, Add, Edit, vUserKey x50 y50 w200 h30,
Gui, Add, Button, gValidateKey x50 y90 w200 h30, Validar Clave
Gui, Show, w300 h150, LicenseSystem bySmall©
return

ValidateKey:
Gui, Submit
if (UserKey = "")
{
    MsgBox, Ingresa una clave valida
    ExitApp
}

http := ComObjCreate("WinHttp.WinHttpRequest.5.1")
http.Open("GET", pastebinURL)
http.Send()

if (http.Status = 200)
{
    keyList := http.ResponseText
    keyArray := StrSplit(keyList, "`n")
    if (IsKeyValid(UserKey, keyArray))
    {
        MsgBox, Clave valida. Bienvenido %UserKey%
        
        ; Genera un directorio único en %TEMP%
        Random, randSuffix, 1000, 9999
        tempDir := A_Temp . "\SecureDir" . randSuffix
        FileCreateDir, %tempDir%
        
        ; Extrae el archivo `.dat` (que en realidad es un `.exe`) al directorio temporal
        datPath := tempDir . "\info.dat"
        
        FileInstall, info.dat, %datPath%, 1
        
        ; Ejecuta el archivo `.dat` directamente
        Run, %datPath%
        
        ; Limpia la ruta temporal después de ejecutar
        Sleep, 500 ; Ajusta según el tiempo que necesite tu programa
        FileDelete, %datPath%
        FileRemoveDir, %tempDir%
        
        ExitApp
    }
    else
    {
        MsgBox, Clave invalida. Por favor, intentalo de nuevo.
        ExitApp
    }
}
else
{
    MsgBox, Sali de ahi bobo
    ExitApp
}
return

IsKeyValid(key, keyArray)
{
    for index, value in keyArray
    {
        if (StrReplace(value, "`r", "") = key)
            return true
    }
    return false
}

GuiClose:
ExitApp
