#NoEnv
#UseHook
#SingleInstance Off
#NoTrayIcon
if not A_IsAdmin
{
Run *RunAs "%A_ScriptFullPath%"
ExitApp
}
SendMode, Input
SetBatchLines, -1
SetKeyDelay, -1, -1
SetControlDelay, -1
Global PROCESS_ALL_ACCESS := 0x001F0FFF
Global PROCESS_VM_READ := 0x10
Global PROCESS_QUERY_INFORMATION := 0x400
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
Global lowerStatusCode := 883
Global higherStatusCode := 887
Hp_bySmallSv := "0x011D1A04"
MaxHP_bySmallSv := "0x011D1A08"
Sp_bySmallSv := "0x011D1A0C"
MaxSP_bySmallSv := "0x011D1A10"
Char_bySmallSv := "0x011D43E8"
Status_bySmallSv := "0x011D1E88"

VersionList := ["bySmallSv"]
IDList := [272, 304, 328, 64, 312, 344, 320]
Memory(Value, HWND_F := 0, Version_F := 0, Size := 23, Temp := "")
{
Global
Str := (Value = "Map" || Value = "Char") ? "String" : ""
HWND_F := HWND_F ? HWND_F : HWND
Version_F := Version_F ? Version_F : Version
If (Value = "Buffs")
{
For Key, Address in Buffs_%Version_F%
{
If (Memory_Read(HWND_F, Address) > 1000)
Break
Temp := Temp "|" Memory_Read(HWND_F, Address)
}
Return Temp "|"
}
Address := InStr(Value, "0x00") ? Value : %Value%_%Version_F%
If !InStr(Value, "0x00")
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
PROCESS_ALL_ACCESS        := 0x001F0FFF
PROCESS_VM_READ           := 0x10
PROCESS_QUERY_INFORMATION := 0x400
TOKEN_ALL_ACCESS        := 0xF01FF
TOKEN_QUERY             := 0x8
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
OnExit, ExitSub
Dir = %A_AppData%\autopot
GBDark := "Black"
IfNotExist, %Dir%
FileCreateDir, %Dir%
IfNotExist, autopotchampbysmall_config.ini
FileAppend, [Settings], autopotchampbysmall_config.ini
FileInstall, images\mastela.gif, %Dir%\mastela.gif
FileInstall, images\blue_potion.gif, %Dir%\blue_potion.gif
FileInstall, images\box_pat.gif, %Dir%\box_pat.gif
FileInstall, images\yggberry.gif, %Dir%\yggberry.gif
FileInstall, images\aloe.gif, %Dir%\aloe.gif
FileInstall, images\seed.gif, %Dir%\seed.gif
Gui, Font, s8, Segoe UI
Gui, Add, GroupBox, xm ym w200 h53, Client
Gui, Add, DDL, xp+25 yp+20 w150 vDDLClient r5 +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")
Gui, Add, GroupBox, xm ym+55 w201 h60, HP
Gui, Add, CheckBox, xp+15 yp+25 w13 h13 vCheckBoxHP,
Gui, Add, Picture, xp+20 yp-6 AltSubmit, %Dir%\mastela.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLHP r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Edit, xp+55 yp w50 vEditHP Number Center Limit3,
Gui, Add, Text, xp+56 yp+3, `%
Gui, Add, GroupBox, xm ym+115 w201 h120, SP
Gui, Add, CheckBox, xp+15 yp+25 w13 h13 vCheckBoxSP,
Gui, Add, Picture, xp+20 yp-6 AltSubmit, %Dir%\box_pat.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLSP r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Edit, xp+55 yp w50 vEditSP Number Center Limit3,
Gui, Add, Text, xp+56 yp+3, `%
Gui, Add, CheckBox, xp-161 yp+35 w13 h13 vCheckBoxYggSP,
Gui, Add, Picture, xp+20 yp-6 AltSubmit, %Dir%\yggberry.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLYggSP r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Edit, xp+55 yp w50 vEditYggSP Number Center Limit3,
Gui, Add, Text, xp+56 yp+3, `%
Gui, Add, CheckBox, xp-161 yp+35 w13 h13 vCheckBoxSeedSP,
Gui, Add, Picture, xp+20 yp-6 AltSubmit, %Dir%\seed.gif
Gui, Add, DDL, xp+30 yp+3 w50 vDDLSeedSP r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Edit, xp+55 yp w50 vEditSeedSP Number Center Limit3,
Gui, Add, Text, xp+56 yp+3, `%
Gui, Add, GroupBox, xm+235 ym+70 w135 h58, Delay
Gui, Add, Edit, xp+17 yp+23 Number Center w100 vEditDelay, 5
Gui, Add, Button, xm+205 ym+150 w100 h25 gButtonStart vButtonStart, Start
Gui, Add, Button, xp yp+28 w100 h25 gButtonClear vButtonClear, Clear
Gui, Add, Button, xp+103 yp-28 w100 h25 gButtonSave vButtonSave, Save
Gui, Add, Button, xp yp+28 w100 h25 gButtonLoad vButtonLoad, Load"
Gui, Add, Text, x290 y30, % "bySmall" Chr(169)



Settings := ["DDLHP", "EditHP", "DDLSP", "EditSP", "DDLYggSP", "EditYggSP", "DDLSeedSP", "EditSeedSP", "EditDelay", "CheckBoxHP", "CheckBoxSP", "CheckBoxYggSP", "CheckBoxSeedSP"]
LoadSettings()
Gui, Font, Bold
Gui, Add, StatusBar
SB_SetText("                                                           HP:  -   SP:  -")
Gui, Show,, AutopotChamp bySmall©
GuiControl, Focus, EditHp
Return
ButtonStart:
Gui, Submit, NoHide
GuiControlGet, Value,, ButtonStart
If (Value = "Start")
{
GuiControlGet, Value,, DDLClient, Text
If !Value
{
SB_SetText("                                                           HP:  -   SP:  -")
SB_HP := "  -   ", SB_SP := "  -"
Return
}
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
V_Char := Memory("Char")
If V_PID
{
Element0 := pName " [" Char "]"
GuiControl,, DDLClient, % Element0
GuiControl, ChooseString, DDLClient, % Element0
}
GuiControl,, ButtonStart, Pausar
GuiControl, Disable, DDLClient
GuiControl, Disable, DDLHP
GuiControl, Disable, EditHP
GuiControl, Disable, DDLSP
GuiControl, Disable, EditSP
GuiControl, Disable, DDLYgg
GuiControl, Disable, EditYgg
GuiControl, Disable, CheckBoxSeedSP
GuiControl, Disable, DDLSeedSP
GuiControl, Disable, EditSeedSP
GuiControl, Disable, EditDelay
GuiControl, Disable, ButtonSave
GuiControl, Disable, ButtonLoad
GuiControl, Disable, ButtonClear
DisableUpdate := 1
For Key, Value in Settings
GuiControl, Disable, % Value
SB_HP := Memory("MaxHP"), SB_SP := Memory("MaxSP")
SB_SetText("                                                     HP:  " SB_HP "   SP:  " SB_SP)
EditDelay := EditDelay < 3 ? 3 : EditDelay
If !Status_%Version%
{
GuiControl, Choose, DDLStatus, 1
DDLStatus := 0
}
If (DDLFly1 && CheckBoxFly1)
Hotkey, ~%DDLFly1%, TpHotkey1, On
If (DDLFly2 && CheckBoxFly2)
Hotkey, ~%DDLFly2%, TpHotkey2, On
SetTimer, ButtonMain, On
}
Else
{
DisableUpdate := 0
GuiControl,, ButtonStart, Start
GuiControl, Enable, DDLClient
GuiControl, Enable, DDLHP
GuiControl, Enable, EditHP
GuiControl, Enable, DDLSP
GuiControl, Enable, EditSP
GuiControl, Enable, DDLYgg
GuiControl, Enable, EditYgg
GuiControl, Enable, DDLSeedSP
GuiControl, Enable, EditSeedSP
GuiControl, Enable, EditDelay
GuiControl, Enable, DDLStatus
GuiControl, Enable, ButtonSave
GuiControl, Enable, ButtonLoad
GuiControl, Enable, ButtonClear
For Key, Value in Settings
GuiControl, Enable, % Value
If (DDLFly1 && CheckBoxFly1)
Hotkey, ~%DDLFly1%, TpHotkey1, Off
If (DDLFly2 && CheckBoxFly2)
Hotkey, ~%DDLFly2%, TpHotkey2, Off
ExitLoop++
}
Return
ButtonMain:
SetTimer, ButtonMain, Off
ExitLoop := 0
Var := 0
Keys := 0

While (!ExitLoop)
{
    ; Aumentar el valor de Var
    Var++
    
    ; Obtener el estado del control EditHP
    GuiControlGet, ControlStatus, Enabled, EditHP
    
    ; Verificar si el control EditHP está habilitado
    If ControlStatus
    {
        ExitLoop++
        Break
    }

    ; Verificar pausa
    if (Pause())
    {
        Sleep, 50
        Continue
    }
    
    ; Verificar condiciones y realizar acciones - SP tiene prioridad
    if (CheckBoxSP && EditSP && DDLSP && Memory("SP") < Memory("MaxSP") * (EditSP / 100))
    {
        Press(DDLSP)
        Sleep, 100
    }

    if (CheckBoxYggSP && EditYggSP && DDLYggSP && Memory("SP") < Memory("MaxSP") * (EditYggSP / 100))
    {
        Press(DDLYggSP)
        Sleep, 100
    }
    
    if (CheckBoxSeedSP && EditSeedSP && DDLSeedSP && Memory("SP") < Memory("MaxSP") * (EditSeedSP / 100))
    {
        Press(DDLSeedSP)
        Sleep, 150
    }

    ; Luego verifica las condiciones para HP
    if (CheckBoxHP && EditHP && DDLHP && Memory("HP") < Memory("MaxHP") * (EditHP / 100))
    {
        Press(DDLHP)
        Sleep, 100
    }
    
    ; Ajustar el retraso entre ciclos
    if (!Keys)
        Sleep, 5
    else if (EditDelay < 5)
        Sleep, 5
    else if (EditDelay >= 15)
        Sleep, % EditDelay
    else
        Sleep(EditDelay)
}
Return

Return
ButtonSave:
Gui, Submit, NoHide
GuiControlGet, Value,, DDLClient, Text
If !Value
Return
If !Version(PID%DDLClient%)
Return
SetTimer, UpdateSB, Off
GuiControl, Enable, % LastControl
PID := PID%DDLClient%
HWND := Memory_GetProcessHandle(PID)
Memory("Char")
For Key, Value in Settings
IniWrite, % %Value%, autopotchampbysmall_config.ini, % Char, % Value
IniWrite, %EditDelay%, autopotchampbysmall_config.ini, % Char, EditDelay
GuiControl, Disable, ButtonSave
LastControl := "ButtonSave"
SB_SetText("                                                   Saved character settings")
SetTimer, UpdateSB, 2000, On
Return
ButtonLoad:
Gui, Submit, NoHide
GuiControlGet, Value,, DDLClient, Text
If !Value
Return
If !Version(PID%DDLClient%)
Return
SetTimer, UpdateSB, Off
GuiControl, Enable, % LastControl
PID := PID%DDLClient%
HWND := Memory_GetProcessHandle(PID)
Memory("Char")
FileRead, FileData, autopotchampbysmall_config.ini
If InStr(FileData, "[" Char "]")
{
SB_SetText("                                                  Loaded character settings")
LoadSettings(Char)
}
Else
SB_SetText("                                                       Character not found")
GuiControl, Disable, ButtonLoad
LastControl := "ButtonLoad"
SetTimer, UpdateSB, 2000, On
Return
ButtonClear:
LoadSettings("Settings", "Clear")
GoSub, UpdateSB
Return
UpdateSB:
If DisableUpdate
Return
SetTimer, UpdateSB, Off
GuiControl, Enable, % LastControl
If SB_H
SB_SetText("                                                     HP:  " SB_HP "   SP:  " SB_SP)
Else
SB_SetText("                                                           HP:  -   SP:  -")
Return
TpHotkey1:
Sleep, 300
Return
TpHotkey2:
Sleep, 300
Return
GuiClose:
ExitApp
ExitSub:
Gui, Submit, NoHide
For Key, Value in Settings
IniWrite, % %Value%, autopotchampbysmall_config.ini, Settings, % Value
IniWrite, %EditDelay%, autopotchampbysmall_config.ini, Settings, EditDelay
ExitApp
ButtonHotkey:
GoSub, ButtonStart
Return
LoadSettings(Section := "Settings", Clear := 0)
{
Global
For Key, Value in Settings
{
If Clear
%Value% := InStr(Value, "CheckBox") ? 0 : ""
Else
IniRead, %Value%, autopotchampbysmall_config.ini, % Section, %Value%, %A_Space%
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
IniRead, ButtonHotkey, autopotchampbysmall_config.ini, % Section, ButtonHotkey, 0
IniRead, EditDelay, autopotchampbysmall_config.ini, % Section, EditDelay, 5
If (EditDelay = "")
EditDelay = 5
GuiControl,, EditDelay, %EditDelay%
If ButtonHotkey
Hotkey, ', ButtonHotkey, On
}
Stuffs(M_GLOOM := 0, M_ASPD := 0)
{
Global
If (Version = "bySmallSv")
Return
If ((DDLASPD && CheckBoxASPD) || (DDLGloom && CheckBoxGloom) || (DDLAloe && CheckBoxAloe))
{
Memory("Buffs")
For Key, Value in CHAR_BUFFS
{
If (Value > 1000)
Return
If (Value = 0)
M_ALOE++
If (Value = 37 || Value = 38 || Value = 39)
M_ASPD++
If (Value = 3)
M_GLOOM++
}
If ((DDLGloom && !M_GLOOM) || (DDLASPD && !M_ASPD) || (DDLAloe && !M_ALOE))
{
Sleep, 1
Memory("Buffs")
}
}
Else If (DDLATKBOX)
Memory("Buffs")
While (DDLATKBOX && CheckBoxATKBOX)
{
For Key, Value in CHAR_BUFFS
{
If (Value > 1000)
Return
If (Value = 150 || Value = 151)
{
Press(DDLATKBOX)
Break 2
}
}
Break
}
While (DDLASPD && CheckBoxASPD)
{
If (Timer("ASPD") && Timer("ASPD") < 150)
Break
For Key, Value in CHAR_BUFFS
{
If (Value > 1000)
Return
If (Value = 37 || Value = 38 || Value = 39)
Break 2
}
Press(DDLASPD)
Timer("ASPD", "On")
Break
}
While (DDLGloom && CheckBoxGloom)
{
If (Timer("Gloom") && Timer("Gloom") < 150)
Break
For Key, Value in CHAR_BUFFS
{
If (Value > 1000)
Return
If (Value = 3)
Break 2
}
Press(DDLGloom)
Timer("Gloom", "On")
Break
}
While (DDLAloe && CheckBoxAloe)
{
For Key, Value in CHAR_BUFFS
{
If (Value > 1000)
Return
If (Value = 0)
Break 2
}
Press(DDLAloe)
Break
}
}
ClientList()
{
Global
V_PID := 0, A_Char := 0
GoSub, UpdateSB
pIndex := 1
WinGet, WinList, List
GuiControl,, DDLClient, |
Loop, %WinList%
{
WinGet, PID0, PID, % "ahk_id" WinList%A_Index%
WinGetTitle, wTitle, ahk_pid %PID0%
If (InStr(wTitle, "AutoPot") || InStr(wTitle, "Spammer"))
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
Memory("MaxSP", HWND0, VersionList[A_Index])
Memory("SP", HWND0, VersionList[A_Index])
Memory("MaxHP", HWND0, VersionList[A_Index])
Memory("HP", HWND0, VersionList[A_Index])
If (HP > 0 && MaxHP > 0 && HP <= 1000000 && MaxHP <= 1000000 && HP <= MaxHP && SP > 0 && MaxSP > 0 && SP <= 1000000 && MaxSP <= 1000000 && SP <= MaxSP)
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
Element%pIndex% := pName " [" Info "]"
GuiControl,, DDLClient, % Element%pIndex%
If (FailCheck && Char = V_Char)
{
GuiControl, ChooseString, DDLClient, % Element%pIndex%
PID := PID0
HWND := Memory_GetProcessHandle(PID0)
FailCheck := 2
SB_HP := Memory("MaxHP"), SB_SP := Memory("MaxSP")
SB_SetText("                                           HP:  " SB_HP "   SP:  " SB_SP)
Return
}
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
Sleep(Delay := 5) {
DllCall("Sleep", "UInt", Delay)
}
Pause() {
Global
If (!WinExist("ahk_pid" PID) || Memory("Char") <> V_Char)
{
SB_SetText("                                                 HP:  -   SP:  -")
SB_HP := 0, SB_SP := 0
If (Char && Char <> V_Char && WinExist("ahk_pid" PID))
{
V_PID := PID
A_Char := Char
WinGet, pName, ProcessName, % "ahk_pid" PID
StringReplace, pName, pName, .exe,, 1
Element0 := pName " [" Char "]"
GuiControl,, DDLClient, % Element0
GuiControl, ChooseString, DDLClient, % Element0
}
Else
{
FailCheck := 1
ClientList()
If (FailCheck = 2)
{
FailCheck := 0
Return
}
FailCheck := 0
}
GoSub, ButtonStart
Return
}
If (Memory("HP") <= 0)
Return 1
KeyList := ["Alt", "Shift", "LWin", "Enter", "Esc", "Control"]
For Index, Value in KeyList
If GetKeyState(Value, "P")
Return 1
}
GetMacAddress(delimiter := ":", case := False)
{
if (DllCall("iphlpapi.dll\GetAdaptersInfo", "ptr", 0, "uint*", size) = 111) && !(VarSetCapacity(buf, size, 0))
throw Exception("Memory allocation failed for IP_ADAPTER_INFO struct", -1)
if (DllCall("iphlpapi.dll\GetAdaptersInfo", "ptr", &buf, "uint*", size) != 0)
throw Exception("GetAdaptersInfo failed with error: " A_LastError, -1)
addr := &buf, MAC_ADDRESS := []
while (addr) {
loop % NumGet(addr+0, 396 + A_PtrSize, "uint")
mac .= Format("{:02" (case ? "X" : "x") "}", NumGet(addr+0, 400 + A_PtrSize + A_Index - 1, "uchar")) "" delimiter ""
MAC_ADDRESS[A_Index] := SubStr(mac, 1, -1), mac := ""
addr := NumGet(addr+0, "uptr")
}
Return MAC_ADDRESS
}
Press(Button) {
Global
If !WinActive("ahk_pid" PID)
ControlSend,, {%Button%}, ahk_pid %PID%
Else
Send, {%Button%}
Keys++
}
Timer(ID, Cmd := "Check")
{
Global
If (Cmd = "Check")
Return A_TickCount - Timer%ID%
Else If (Cmd = "On")
Timer%ID% := A_TickCount
Else If (Cmd = "Off")
Timer%ID% := ""
}