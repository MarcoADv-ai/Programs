; Archivo de configuración
ConfigFile := "HWChainConfigBySmall~.ini"
Injected := 0  ; Bandera para verificar si se hizo clic en "Inyectar"

; Valores predeterminados
Default_sglovDelay := 2000
Default_meteorDelay := 1
Default_sglovprecastDelay := 350

; Leer o crear el archivo de configuración
IniRead, vsglovDelay, %ConfigFile%, Delays, SGLOVDelay, %Default_sglovDelay%
IniRead, vmeteorDelay, %ConfigFile%, Delays, MeteorDelay, %Default_meteorDelay%
IniRead, vsglovprecastDelay, %ConfigFile%, Delays, SGLOVPrecastDelay, %Default_sglovprecastDelay%

; Si no existen las claves, escribir los valores predeterminados
If !FileExist(ConfigFile) {
    IniWrite, %Default_sglovDelay%, %ConfigFile%, Delays, SGLOVDelay
    IniWrite, %Default_meteorDelay%, %ConfigFile%, Delays, MeteorDelay
    IniWrite, %Default_sglovprecastDelay%, %ConfigFile%, Delays, SGLOVPrecastDelay
}

#NoTrayIcon
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

OnExit, ExitSub
Gui, Font, s8, Segoe UI
Gui, Color, CFCFFF
Gui, Add, GroupBox, xm ym w350 h60, Client
Gui, Add, DDL, xp+25 yp+20 w300 vDDLClient r5 +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")
Gui, Add, Text, x10 y90, Meteor Key:
Gui, Add, Edit, x100 y90 w50 vmeteor, F1
Gui, Add, Text, x10 y120, SG/LOV Key:
Gui, Add, Edit, x100 y120 w50 vsg, F2
Gui, Add, Text, x10 y150, Thunder Key:
Gui, Add, Edit, x100 y150 w50 vlov, F3
Gui, Add, Text, x10 y180, SG/LOV Sequence (ms):
Gui, Add, Edit, x130 y180 w50 vsglovDelay, %vsglovDelay%
Gui, Add, Text, x10 y210, Meteor Cast Delay (ms):
Gui, Add, Edit, x130 y210 w50 vmeteorDelay, %vmeteorDelay%
Gui, Add, Text, x10 y240, SG/Lov Cast Delay (ms):
Gui, Add, Edit, x130 y240 w50 vsglovprecastDelay, %vsglovprecastDelay%
Gui, Add, StatusBar
Gui, Font, Bold
Gui, Add, Button, xm+125 ym+280 w100 h35 gButtonStart vButtonStart, Inyectar
Gui, Show,, HW Pro Chain 
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



$F5::
    ; Verificar si se hizo clic en "Inyectar"
    If (Injected = 0)
    {
	  MsgBox, Por favor Inyecta el programa antes de continuar.
	exitapp
    }

    Gui, Submit, NoHide
    ChainWizard := 0
    GuiControlGet, meteor
    GuiControlGet, sg
    GuiControlGet, lov
    GuiControlGet, sglovDelay
    sglovDelay := sglovDelay + 0
    GuiControlGet, meteorDelay
    meteorDelay := meteorDelay + 0
    key_meteor := meteor
    key_sg := sg
    key_lov := lov
    LastSGLOVTime := A_TickCount - sglovDelay
        While GetKeyState("F5", "P")
        {
            SendInput, {%key_meteor%}
            DLLClick()
            Sleep(meteorDelay)
            if (A_TickCount - LastSGLOVTime >= sglovDelay)
            {
                if (ChainWizard == 0)
                {
                    Sleep(sglovprecastDelay)
                    SendInput, {%key_sg%}
                    DLLClick()
                    Sleep(sglovprecastDelay)
                    SendInput, {%key_sg%}
                    DLLClick()
                    Sleep(sglovprecastDelay)
                    Sleep(sglovprecastDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    Sleep(meteorDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    Sleep(meteorDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    ChainWizard := 1
                }
                else
                {
                    Sleep(sglovprecastDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    Sleep(meteorDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    Sleep(meteorDelay)
                    SendInput, {%key_lov%}
                    DLLClick()
                    ChainWizard := 0
                }
                LastSGLOVTime := A_TickCount
            }
        }  
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

