; Archivo de configuración
ConfigFile := "SwapCrossConfigBySmall~.ini"
Injected := 0  ; Bandera para verificar si se hizo clic en "Inyectar"

; Valores predeterminados
Default_katar := "U"
Default_headatk := "Y"
Default_boots := "I"
Default_garmentatk := "O"
Default_shield := "H"
Default_weapon := "G"
Default_headdef := "J"
Default_bootsdef := "K"
Default_garmentdef := "L"

; Leer o crear el archivo de configuración
IniRead, katar, %ConfigFile%, Teclas, Katar, %Default_katar%
IniRead, headatk, %ConfigFile%, Teclas, HeadgearAtk, %Default_headatk%
IniRead, boots, %ConfigFile%, Teclas, Boots, %Default_boots%
IniRead, garmentatk, %ConfigFile%, Teclas, GarmentAtk, %Default_garmentatk%
IniRead, shield, %ConfigFile%, Teclas, Shield, %Default_shield%
IniRead, weapon, %ConfigFile%, Teclas, Weapon, %Default_weapon%
IniRead, headdef, %ConfigFile%, Teclas, HeadgearDef, %Default_headdef%
IniRead, bootsdef, %ConfigFile%, Teclas, BootsDef, %Default_bootsdef%
IniRead, garmentdef, %ConfigFile%, Teclas, GarmentDef, %Default_garmentdef%

; Si no existen las claves, escribir los valores predeterminados
If !FileExist(ConfigFile) {
    IniWrite, %Default_katar%, %ConfigFile%, Teclas, Katar
    IniWrite, %Default_headatk%, %ConfigFile%, Teclas, HeadgearAtk
    IniWrite, %Default_boots%, %ConfigFile%, Teclas, Boots
    IniWrite, %Default_garmentatk%, %ConfigFile%, Teclas, GarmentAtk
    IniWrite, %Default_shield%, %ConfigFile%, Teclas, Shield
    IniWrite, %Default_weapon%, %ConfigFile%, Teclas, Weapon
    IniWrite, %Default_headdef%, %ConfigFile%, Teclas, HeadgearDef
    IniWrite, %Default_bootsdef%, %ConfigFile%, Teclas, BootsDef
    IniWrite, %Default_garmentdef%, %ConfigFile%, Teclas, GarmentDef
}

#NoEnv
#UseHook
#SingleInstance Off
#MaxHotkeysPerInterval 999999999999999
#Include Libs\SMemory_Lib.ahk
#Persistent
SetDefaultMouseSpeed, 0
SetTitleMatchMode, 2
DetectHiddenWindows, On
SendMode, Input
SetBatchLines, -1

if not A_IsAdmin
{
   Run *RunAs "%A_ScriptFullPath%"
   ExitApp
}
; Crear la carpeta "Imagenes" si no existe
gifFolder := A_ScriptDir . "\Imagenes"
FileCreateDir, %gifFolder%

; Guardar los archivos .gif en la carpeta "Imagenes"
FileInstall, images\sonicblow.gif, %gifFolder%\sonicblow.gif, 1
FileInstall, images\grim.gif, %gifFolder%\grim.gif, 1
FileInstall, images\Katar.gif, %gifFolder%\Katar.gif, 1
FileInstall, images\headatk.gif, %gifFolder%\headatk.gif, 1
FileInstall, images\vmanta.gif, %gifFolder%\vmanta.gif, 1
FileInstall, images\shackles.gif, %gifFolder%\shackles.gif, 1
FileInstall, images\ck.gif, %gifFolder%\ck.gif, 1
FileInstall, images\vshield.gif, %gifFolder%\vshield.gif, 1
FileInstall, images\dmanta.gif, %gifFolder%\dmanta.gif, 1
FileInstall, images\dbotas.gif, %gifFolder%\dbotas.gif, 1
FileInstall, images\headdef.gif, %gifFolder%\headdef.gif, 1

OnExit, ExitSub
Gui, Font, s8, Segoe UI
Gui, Color, 63e5ff
Gui, Add, GroupBox, xm ym w350 h60, Client
Gui, Add, DDL, xp+25 yp+20 w300 vDDLClient r5 +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")
Gui, Add, Text, x10 y70, ATK Gears:
Gui, Add, Picture, x10 y90 w20 h20, %gifFolder%\katar.gif
Gui, Add, Edit, x80 y90 w50 vkatar, %katar%
Gui, Add, Picture, x10 y120 w20 h20, %gifFolder%\headatk.gif
Gui, Add, Edit, x80 y120 w50 vheadatk, %headatk%
Gui, Add, Picture, x10 y150 w20 h20, %gifFolder%\shackles.gif
Gui, Add, Edit, x80 y150 w50 vboots, %boots%
Gui, Add, Picture, x10 y180 w20 h20, %gifFolder%\vmanta.gif
Gui, Add, Edit, x80 y180 w50 vgarmentatk, %garmentatk%

Gui, Add, Text, x220 y70, DEF Gears:
Gui, Add, Picture, x220 y90 w20 h20, %gifFolder%\vshield.gif
Gui, Add, Edit, x280 y90 w50 vshield, %shield%
Gui, Add, Picture, x220 y120 w20 h20, %gifFolder%\ck.gif
Gui, Add, Edit, x280 y120 w50 vweapon, %weapon%
Gui, Add, Picture, x220 y150 w20 h20,%gifFolder%\headdef.gif
Gui, Add, Edit, x280 y150 w50 vheaddef, %headdef%
Gui, Add, Picture, x220 y180 w20 h20, %gifFolder%\dmanta.gif
Gui, Add, Edit, x280 y180 w50 vgarmentdef, %garmentdef%
Gui, Add, Picture, x220 y210 w20 h20, %gifFolder%\dbotas.gif
Gui, Add, Edit, x280 y210 w50 vbootsdef, %bootsdef%

Gui, Add, StatusBar
Gui, Font, Bold
Gui, Add, Button, xm+125 ym+280 w100 h35 gButtonStart vButtonStart, Inyectar
Gui, Add, Text, x300 y340, % "bySmall" Chr(169)
Gui, Show,, Cross Pro Swap 
SB_SetText("  Inyecta el programa en la memoria del cliente para continuar..")
Return

MouseFixAddress = 0xE2EC84

ButtonStart:
    Gui, Submit, NoHide
    If V_PID
    {
        If !Version(V_PID)
        {
            V_PID := 0, A_Char := 0
            ClientList()
            Return
        }
    }
    Else If !Version(PID%DDLClient%)
        Return
    PID := V_PID ? V_PID : PID%DDLClient%
    HWND := Memory_GetProcessHandle(PID)
    WinGet, hwndGame, ID, ahk_pid %PID%
    V_Char := Memory("Char")
    Loop, 5{
        SB_SetText(" Inyectando. ")
        Sleep(5)
        SB_SetText(" Inyectando.. ")
        Sleep(5)
        SB_SetText(" Inyectando... ")
        Sleep(5)
    }
    SB_SetText(" Leyendo Memoria correctamente ")
    GuiControl, Hide, ButtonStart
    GuiControl, Disable, DDLClient
    Injected := 1  ; Activar bandera para indicar que el programa está listo
Return



$F3::
    ; Verificar si se hizo clic en "Inyectar"
    If (Injected = 0)
    {
	  MsgBox, Por favor Inyecta el programa antes de continuar.
	exitapp
    }

    Gui, Submit, NoHide
    GuiControlGet, katar
    GuiControlGet, headatk
    GuiControlGet, boots
    GuiControlGet, garmentatk
    GuiControlGet, shield
    GuiControlGet, weapon
    GuiControlGet, headdef
    GuiControlGet, bootsdef
    GuiControlGet, garmentdef
Send, {%katar%}
Sleep, 120
Send, {%headatk%}
Sleep, 120
Send, {%boots%}
Sleep, 120
Send, {%garmentatk%}
Sleep, 120
Return

$1::
    ; Verificar si se hizo clic en "Inyectar"
    If (Injected = 0)
    {
	  MsgBox, Por favor Inyecta el programa antes de continuar.
	exitapp
    }

    Gui, Submit, NoHide
    GuiControlGet, katar
    GuiControlGet, headatk
    GuiControlGet, boots
    GuiControlGet, garmentatk
    GuiControlGet, shield
    GuiControlGet, weapon
    GuiControlGet, headdef
    GuiControlGet, bootsdef
    GuiControlGet, garmentdef
Send, {%shield%}
Sleep, 120
Send, {%weapon%}
Sleep, 120
Send, {%headdef%}
Sleep, 120
Send, {%garmentdef%}
Sleep, 120
Send, {%bootsdef%}

Return

DLLClick()
{
   Memory_Write(HWND, MouseFixAddress, 4294967295)
   DllCall("mouse_event", "UInt", 0x02)
   Sleep(1)
   DllCall("mouse_event", "UInt", 0x04)
   Memory_Write(HWND, MouseFixAddress, 500)
}

Sleep(Delay := 1) {
    DllCall("Sleep", "UInt", Delay)
}

GuiClose:
    ExitApp

ExitSub:
    Gui, Submit, NoHide
    ExitApp
End::Suspend

