; <COMPILER: v1.1.36.02>
#Persistent
#NoEnv
#UseHook
#SingleInstance Off
#NoTrayIcon
#MaxHotkeysPerInterval 999999999999999
SetDefaultMouseSpeed, 0
SetTitleMatchMode, 2
SetBatchLines, -1
SetKeyDelay, -1, -1
SetControlDelay, -1
if not A_IsAdmin
{
    Run *RunAs "%A_ScriptFullPath%"
    ExitApp
}
Global PROCESS_ALL_ACCESS := 0x001F0FFF
Global PROCESS_VM_READ := 0x10
Global PROCESS_QUERY_INFORMATION := 0x400
Global PID  ; Se guardará el PID de la ventana seleccionada
Global TOKEN_ALL_ACCESS := 0xF01FF
Global TOKEN_QUERY := 0x8
Global TOKEN_ADJUST_PRIVILEGES := 0x20
Global SE_PRIVILEGE_ENABLED := 2
Global PAGE_READWRITE := 0x04
Global PAGE_EXECUTE_READWRITE := 0x40
Global TH32CS_SNAPMODULE := 0x00000008
Global INVALID_HANDLE_VALUE := -1
Global MODULEENTRY32_SIZE := 548
Global MODULEENTRY32_modBaseAddr := 20
Global MODULEENTRY32_hModule := 29
Global MODULEENTRY32_szModule := 32
Global max_buff := 100
Global critical_explosion := 86
Hp_Tribe := "0x011D1A04"
MaxHP_Tribe := "0x011D1A08"
Sp_Tribe := "0x011D1A0C"
MaxSP_Tribe := "0x011D1A10"
Char_Tribe := "0x011D43E8"
Status_Tribe := "0x011D1E84"
Weapon_Tribe := "0x00E492A4"
VersionList := ["Tribe"]
IDList := [272, 304, 328, 64, 312, 344, 320]
Memory(Value, HWND_F := 0, Version_F := 0, Size := 23, Temp := "")
{
Global
Str := (Value = "Map" || Value = "Char") ? "String" : ""
HWND_F := HWND_F ? HWND_F : HWND
Version_F := Version_F ? Version_F : Version
If (Value = "Status")
{
i := 0
While, i < max_buff
{
Address := %Value%_%Version_F% + (i * 4)
Address := FormatAddress(address)
status := Memory_Read(HWND_F, Address)
If (status == critical_explosion)
Return, 1
i++
}
Return, 0
}
Address := InStr(Value, "0x0") ? Value : %Value%_%Version_F%
If !InStr(Value, "0x0")
%Value% := Memory_Read%Str%(HWND_F, Address, Size)
Return Memory_Read%Str%(HWND_F, Address, Size)
}
Version(ExeName := 0)
{
Global
If !ExeName
Return
Process, Exist, %ExeName%
PID0 := ErrorLevel
HWND0 := Memory_GetProcessHandle(PID0)
For Key, Value in VersionList
{
Memory("MaxSP", HWND0, Value)
If (MaxSP > 0 && MaxSP < 1000000)
{
Version := Value
WinGetTitle, WinTitle, ahk_pid %PID0%
Return Version
}
}
}
Memory_GetProcessID(process_name)
{
Process, Exist, %process_name%
process_id = %ErrorLevel%
Return, process_id
}
Memory_GetProcessHandle(process_id)
{
process_handle := DllCall("OpenProcess", "UInt", 0x001F0FFF, "Int", false, "UInt", process_id, "Ptr")
Return, process_handle
}
Memory_GetModuleBase(process_id, module_name)
{
snapshot_handle := DllCall("CreateToolhelp32Snapshot", "UInt", 0x00000008, "UInt", process_id)
If (snapshot_handle = INVALID_HANDLE_VALUE)
{
Return, False
}
VarSetCapacity(me32, 548, 0)
NumPut(548, me32)
If (DllCall("Module32First", "UInt", snapshot_handle, "UInt", &me32))
{
While (DllCall("Module32Next", "UInt", snapshot_handle, "UInt", &me32))
{
If (module_name == StrGet(&me32 + 32, 256, "CP0"))
{
DllCall("CloseHandle", "UInt", snapshot_handle)
Return, NumGet(&me32, 20)
}
}
}
DllCall("CloseHandle", "UInt", snapshot_handle)
Return, False
}
Memory_CloseHandle(process_handle)
{
DllCall("CloseHandle", "Ptr", process_handle)
}
Memory_Read(process_handle, address)
{
VarSetCapacity(value, 4, 0)
DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", value, "UInt", 4, "UInt *", 0)
Return, NumGet(value, 0, "UInt")
}
Memory_ReadEx(process_handle, address, size)
{
VarSetCapacity(value, size, 0)
DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", value, "UInt", size, "UInt *", 0)
Return, NumGet(value, 0, "UInt")
}
Memory_ReadFloat(process_handle, address)
{
VarSetCapacity(value, 4, 0)
DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", value, "UInt", 4, "UInt *", 0)
Return, NumGet(value, 0, "Float")
}
Memory_ReadReverse(process_handle, address)
{
VarSetCapacity(value, 4, 0)
DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", value, "UInt", 4, "UInt *", 0)
Return, *(&value + 3) | *(&value + 2) << 8 | *(&value + 1) << 16 | *(&value) << 24
}
Memory_ReadString(process_handle, address, size)
{
VarSetCapacity(value, size, 0)
DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", value, "UInt", size, "UInt *", 0)
Loop, %size%
{
current_value := NumGet(value, A_Index - 1, "UChar")
If (current_value = 0)
{
Break
}
result .= Chr(current_value)
}
Return, result
}
Memory_ReadStringEx(process_handle, address, size)
{
result =
Loop, %size%
{
output := "x"
read := DllCall("ReadProcessMemory", "UInt", process_handle, "UInt", address, "Str", output, "UInt", 1, "UInt *", 0)
If (ErrorLevel or !read)
{
Return, result
}
If output =
{
Break
}
output_character := *(&output)
IfEqual, output_character, 32
{
result .= " "
}
Else
{
result = %result%%output%
}
address++
}
Return, result
}
Memory_Write(process_handle, address, value)
{
DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", 4, "UInt", 0x04, "UInt *", 0)
DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UInt *", value, "UInt", 4, "UInt *", 0)
}
Memory_WriteEx(process_handle, address, value, size)
{
DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", size, "UInt", 0x04, "UInt *", 0)
DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UInt *", value, "UInt", size, "UInt *", 0)
}
Memory_WriteFloat(process_handle, address, value)
{
value := FloatToHex(value)
DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", 4, "UInt", 0x04, "UInt *", 0)
DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UInt *", value, "UInt", 4, "UInt *", 0)
}
Memory_WriteNops(process_handle, address, size)
{
If (size <= 0)
{
Return
}
VarSetCapacity(value, size)
Loop, %size%
{
NumPut(0x90, value, A_Index - 1, "UChar")
}
DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", size, "UInt", 0x04, "UInt *", 0)
DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UInt", &value, "UInt", size, "UInt *", 0)
}
Memory_WriteBytes(process_handle, address, bytes)
{
bytes_size := 0
Loop, Parse, bytes, `,
{
bytes_size += 1
}
VarSetCapacity(value, bytes_size)
Loop, Parse, bytes, `,
{
byte = 0x%A_LoopField%
NumPut(byte, value, A_Index - 1, "UChar")
}
DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", bytes_size, "UInt", 0x04, "UInt *", 0)
DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UInt", &value, "UInt", bytes_size, "UInt *", 0)
}
FloatToHex(value)
{
format := A_FormatInteger
SetFormat, Integer, H
result := DllCall("MulDiv", Float, value, Int, 1, Int, 1, UInt)
SetFormat, Integer, %format%
Return, result
}
EnableDebugPrivileges()
{
PROCESS_ALL_ACCESS := 0x001F0FFF
PROCESS_VM_READ := 0x10
PROCESS_QUERY_INFORMATION := 0x400
TOKEN_ALL_ACCESS := 0xF01FF
TOKEN_QUERY := 0x8
TOKEN_ADJUST_PRIVILEGES := 0x20
SE_PRIVILEGE_ENABLED := 2
Process, Exist
h := DllCall("OpenProcess", "UInt", PROCESS_QUERY_INFORMATION, "Int", false, "UInt", ErrorLevel, "Ptr")
DllCall("Advapi32.dll\OpenProcessToken", "Ptr", h, "UInt", TOKEN_ADJUST_PRIVILEGES, "PtrP", t)
VarSetCapacity(ti, 16, 0)
NumPut(1, ti, 0, "UInt")
DllCall("Advapi32.dll\LookupPrivilegeValue", "Ptr", 0, "Str", "SeDebugPrivilege", "Int64P", luid)
NumPut(luid, ti, 4, "Int64")
NumPut(SE_PRIVILEGE_ENABLED, ti, 12, "UInt")
r := DllCall("Advapi32.dll\AdjustTokenPrivileges", "Ptr", t, "Int", false, "Ptr", &ti, "UInt", 0, "Ptr", 0, "Ptr", 0)
DllCall("CloseHandle", "Ptr", t)
DllCall("CloseHandle", "Ptr", h)
}
ClientList()
{
Global
pIndex := 1
WinGet, WinList, List
GuiControl,, DDLClient, |
Loop, %WinList%
{
WinGet, PID0, PID, % "ahk_id" WinList%A_Index%
WinGetTitle, wTitle, ahk_pid %PID0%
If InStr(wTitle, "Fast Refresh")
Continue
HWND0 := Memory_GetProcessHandle(PID0)
ClientID := Memory(0x0040003C, HWND0)
Loop,
{
If !IDList[A_Index]
Continue 2
If (ClientID = IDList[A_Index])
Break
}
Loop,
{
If (Memory("MaxSP", HWND0, VersionList[A_Index]) > 0 && MaxSP <= 1000000)
{
Memory("Char", HWND0, VersionList[A_Index])
Break
}
If !VersionList[A_Index]
Continue 2
}
PID%pIndex% := PID0
WinGet, pName, ProcessName, % "ahk_id" WinList%A_Index%
StringReplace, pName, pName, .exe,, 1
Info := Char ? Char : PID0
Element := pName " [" Info "]"
GuiControl,, DDLClient, %Element%
pIndex++
}
}
On_CBN_DROPDOWN(wParam, lParam)
{
Static CBN_DROPDOWN := 7
Critical
GuiControlGet, Gui_Control, %A_Gui%:Name, %lParam%
If (Gui_Control = "DDLClient") && (wParam >> 16 = CBN_DROPDOWN) {
ClientList()
}
}
Sleep(Delay := 5)
{
DllCall("Sleep", "UInt", Delay)
}
Delay(D = 0.001)
{
Static F
Critical
F ? F : DllCall("QueryPerformanceFrequency", Int64P, F)
DllCall("QueryPerformanceCounter", Int64P, pTick), cTick := pTick
While(((Tick := (pTick - cTick) / F)) < D)
{
DllCall( "QueryPerformanceCounter", Int64P,pTick )
Sleep -1
}
Return Round(Tick, 3)
}
Press(Key)
{
Global
If !DllMode
{
IfWinActive, ahk_pid %PID%
Send, {%Key%}
Else
ControlSend,, {%Key%}, ahk_pid %PID%
}
Else If WinActive("ahk_pid" PID)
DD._key_press(Key)
}
LoadSettings(Section := "Settings")
{
Global
For Key, Value in Settings
{
IniRead, %Value%, switch_cfg.ini, % Section, %Value%, %A_Space%
If InStr(Value, "DDL")
{
If (%Value% = "f")
{
GuiControl, Choose, %Value%, 25
Continue
}
Else If (%Value%)
SubCommand := "ChooseString"
Else
{
GuiControl, Choose, %Value%, 1
Continue
}
}
Else
SubCommand := ""
GuiControl, %SubCommand%, %Value%, % %Value%
}
IniRead, ButtonHotkey, switch_cfg.ini, % Section, ButtonHotkey, 0
IniRead, EditDelay, switch_cfg.ini, % Section, EditDelay, 10
If (EditDelay = "")
EditDelay = 10
GuiControl,, EditDelay, %EditDelay%
If ButtonHotkey
Hotkey, ', ButtonHotkey, On
}
FormatAddress(address)
{
address := Format("{:X}", address)
length := StrLen(address)
length := 8 - length
Loop, %length%
{
address := "0" + address
}
address := "0x" + address
Return address
}
OnExit, ExitSub
Dir = %A_AppData%\louri tools
IfNotExist, %Dir%
FileCreateDir, %Dir%
IfNotExist, switch_cfg.ini
FileAppend, [Settings], switch_cfg.ini
FileInstall, images\asura.gif, %Dir%\asura.gif, 1
FileInstall, images\zen.gif, %Dir%\zen.gif, 1
FileInstall, images\passo.gif, %Dir%\passo.gif, 1
FileInstall, images\asura_weapon.gif, %Dir%\asura_weapon.gif, 1
FileInstall, images\aspd_weapon.gif, %Dir%\aspd_weapon.gif, 1
FileInstall, images\critical_explosion.gif, %Dir%\critical_explosion.gif, 1
FileInstall, images\glorious.gif, %Dir%\glorious.gif, 1
Gui, Font, s8, Segoe UI
Gui, Add, GroupBox, xm ym w180 h53, Client
Gui, Add, DDL, xp+15 yp+20 r2 w150 vDDLClient +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")
Gui, Add, GroupBox, xm ym+55 w180 h260, Hotkeys
Gui, Add, Picture, xp+50 yp+20 AltSubmit, %Dir%\asura.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLAsura r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Picture, xp-30 yp+30 AltSubmit, %Dir%\zen.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLZen r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Picture, xp-30 yp+30 AltSubmit, %Dir%\critical_explosion.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLCritical r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Picture, xp-30 yp+30 AltSubmit, %Dir%\asura_weapon.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLMace r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Picture, xp-30 yp+30 AltSubmit, %Dir%\glorious.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLGlorious r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Picture, xp-30 yp+33 AltSubmit, %Dir%\aspd_weapon.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLBerserk r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Button, xm+5 ym+320 w170 h30 vButtonStart, Start
Settings := ["DDLAsura", "DDLZen", "DDLCritical", "DDLPasso", "DDLMace", "DDLGlorious", "DDLBerserk"]
LoadSettings()
Gui, Show,, Monk Switch
Return
ButtonStart:
Gui, Submit, NoHide
GuiControlGet, Value,, ButtonStart
If (Value = "Start")
{
GuiControlGet, Value,, DDLClient, Text
If !Version(PID%DDLClient%)
Return
PID := PID%DDLClient%
HWND := Memory_GetProcessHandle(PID)
GuiControl,, ButtonStart, Stop
GuiControl, Disable, DDLClient
GuiControl, Disable, DDLAsura
GuiControl, Disable, DDLZen
GuiControl, Disable, DDLCritical
GuiControl, Disable, DDLPasso
GuiControl, Disable, DDLMace
GuiControl, Disable, DDLGlorious
GuiControl, Disable, DDLBerserk
ExitLoop := 0
If DDLAsura
Hotkey, $%DDLAsura%, Asura
If DDLZen
Hotkey, $%DDLZen%, Zen
If DDLPasso
Hotkey, $%DDLPasso%, Passo
}
Else
{
GuiControl,, ButtonStart, Start
GuiControl, Enable, DDLClient
GuiControl, Enable, DDLAsura
GuiControl, Enable, DDLZen
GuiControl, Enable, DDLCritical
GuiControl, Enable, DDLPasso
GuiControl, Enable, DDLMace
GuiControl, Enable, DDLGlorious
GuiControl, Enable, DDLBerserk
ExitLoop++
}
Return
CheckPID()
{
    WinGet, currentPID, PID, A  ; Obtiene el PID de la ventana activa
    Return (currentPID = PID)  ; Compara con el PID guardado
}

Asura:
{
    While, GetKeyState(DDLAsura, "P")
    {
        ; Verificar si el PID es el correcto antes de ejecutar acciones
        If (CheckPID())
        {
            ; Si el valor de Memory("Status") es verdadero
            If (Memory("Status"))
            {
                ControlSend,, {%DDLMace%}, A    ; Enviar el valor de DDLMace a la ventana activa
                ControlSend,, {%DDLAsura%}, A   ; Enviar el valor de DDLAsura a la ventana activa
                Click                          ; Realizar clic izquierdo en la posición actual
            }
            Else
            {
                ControlSend,, {%DDLGlorious%}, A  ; Enviar el valor de DDLGlorious a la ventana activa
                ControlSend,, {%DDLCritical%}, A   ; Enviar el valor de DDLCritical a la ventana activa
            }
            Sleep(1)  ; Pausa de 1 milisegundo entre acciones
        }
    }
    Return
}

Zen:
{
    ; Verificar si el PID es el correcto antes de ejecutar acciones
    If (CheckPID())
    {
        ControlSend,, {%DDLBerserk%}, A   ; Enviar el valor de DDLBerserk a la ventana activa
        ControlSend,, {%DDLZen%}, A       ; Enviar el valor de DDLZen a la ventana activa
    }
    Return
}
GuiClose:
ExitApp
ExitSub:
Gui, Submit, NoHide
For Key, Value in Settings
IniWrite, % %Value%, switch_cfg.ini, Settings, % Value
IniWrite, %EditDelay%, switch_cfg.ini, Settings, EditDelay
ExitApp
ButtonHotkey:
GoSub, ButtonStart
Return