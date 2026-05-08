; <COMPILER: v1.1.36.02>
#NoEnv
#UseHook
#SingleInstance Off
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
Hp_2018a := "0x010DCE10"
MaxHP_2018a := "0x010DCE14"
Sp_2018a := "0x010DCE18"
MaxSP_2018a := "0x010DCE1C"
Status_2018a := "0x010DD284"
Char_2018a := "0x010DF5D8"
x_2018a := "0x011BA5A0"
y_2018a := "0x011BA5A4"
map_2018a := "0x011CCF68"
VersionList := ["2018a"]
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
ReadMemory(MADDRESS,PID)
{
VarSetCapacity(MVALUE,4,0)
ProcessHandle := DllCall("OpenProcess", "Int", 24, "Char", 0, "UInt", pid, "UInt")
DllCall("ReadProcessMemory", "UInt", ProcessHandle, "Ptr", MADDRESS, "Ptr", &MVALUE, "Uint",4)
Loop 4
result += *(&MVALUE + A_Index-1) << 8*(A_Index-1)
return, result
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
Sleep(Delay := 5)
{
DllCall("Sleep", "UInt", Delay)
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
LoadSettings(Section := "Settings")
{
Global
For Key, Value in Settings
{
IniRead, %Value%, teleport_conf.ini, % Section, %Value%, %A_Space%
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
IniRead, ButtonHotkey, teleport_conf.ini, % Section, ButtonHotkey, 0
If ButtonHotkey
Hotkey, ', ButtonHotkey, On
}
OnExit, ExitSub
Dir = %A_AppData%\louri tools
IfNotExist, %Dir%
FileCreateDir, %Dir%
IfNotExist, teleport_conf.ini
FileAppend, [Settings], teleport_conf.ini
FileInstall, images\fly_wing.gif, %Dir%\fly_wing.gif, 1
Gui, Font, s8, Segoe UI
Gui, Add, GroupBox, xm ym w180 h53, Client
Gui, Add, DDL, xp+15 yp+20 r2 w150 vDDLClient +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")
Gui, Add, GroupBox, xm ym+55 w180 h62, Hotkey
Gui, Add, Picture, xp+43 yp+20 AltSubmit, %Dir%\fly_wing.gif
Gui, Add, DDL, xp+44 yp+3 w50 vDDLFly r10, |F1|F2|F3|F4|F5|F6|F7|F8|F9|1|2|3|4|5|6|7|8|9|A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
Gui, Add, Button, xm+5 ym+125 w170 h30 vButtonStart, Start
Settings := ["DDLFly"]
LoadSettings()
Gui, Show,, TP DeuS
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
GuiControl, Disable, DDLFly
}
Else
{
GuiControl,, ButtonStart, Start
GuiControl, Enable, DDLClient
GuiControl, Enable, DDLFly
}
Return
End::
ExitLoop++
Return
$Numpad0::
ExitLoop := 0
map := Memory("map")
switch map
{
case "lhz_dun01":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 2 && y >= 142 && x <= 69 && y <= 204) || (x >= 229 && y >= 149 && x <= 296 && y <= 182) || (x >= 75 && y >= 3 && x <= 181 && y <= 37))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 112 && y >= 119 && x <= 157 && y <= 156)
break
}
case "odin_tem01":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 326 && y >= 138 && x <= 396 && y <= 372)
break
}
case "odin_tem02":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 137 && y >= 265 && x <= 359 && y <= 390)
break
}
default:
Send, {Numpad0}
}
Return
$Numpad1::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 5 && y >= 12 && x <= 69 && y <= 119)
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 17 && y >= 45 && x <= 91 && y <= 86)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 21 && y >= 21 && x <= 152 && y <= 137)
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 7 && y >= 14 && x <= 125 && y <= 157)
break
}
default:
Send, {Numpad1}
}
Return
$Numpad2::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 71 && y >= 26 && x <= 93 && y <= 115) || (x >= 83 && y >= 2 && x <= 181 && y <= 45))
break
}
case "lhz_dun01":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 2 && y >= 143 && x <= 49 && y <= 193) || (x >= 259 && y >= 148 && x <= 298 && y <= 182) || (x >= 118 && y >= 2 && x <= 158 && y <= 36))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 129 && y >= 68 && x <= 150 && y <= 108) || (x >= 119 && y >= 87 && x <= 129 && y <= 104) || (x >= 151 && y >= 86 && x <= 161 && y <= 104))
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 83 && y >= 25 && x <= 129 && y <= 63) || (x >= 116 && y >= 18 && x <= 259 && y <= 99) || (x >= 138 && y >= 95 && x <= 193 && y <= 188) || (x >= 227 && y >= 21 && x <= 301 && y <= 114))
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 179 && y >= 1 && x <= 276 && y <= 80) || (x >= 166 && y >= 76 && x <= 250 && y <= 197))
break
}
default:
Send, {Numpad2}
}
Return
$Numpad3::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 173 && y >= 9 && x <= 239 && y <= 57) || (x >= 211 && y >= 47 && x <= 239 && y <= 91))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 184 && y >= 46 && x <= 255 && y <= 86)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 206 && y >= 22 && x <= 273 && y <= 101) || (x >= 212 && y >= 108 && x <= 271 && y <= 187) || (x >= 258 && y >= 22 && x <= 378 && y <= 143))
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 283 && y >= 58 && x <= 357 && y <= 179) || (x >= 268 && y >= 75 && x <= 288 && y <= 140))
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 139 && y >= 16 && x <= 285 && y <= 159)
break
}
default:
Send, {Numpad3}
}
Return
$Numpad4::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 4 && y >= 107 && x <= 93 && y <= 191)
break
}
case "lhz_dun01":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 2 && y >= 143 && x <= 49 && y <= 193) || (x >= 259 && y >= 148 && x <= 298 && y <= 182) || (x >= 118 && y >= 2 && x <= 158 && y <= 36))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 56 && y >= 123 && x <= 89 && y <= 155)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 22 && y >= 119 && x <= 76 && y <= 144) || (x >= 22 && y >= 132 && x <= 117 && y <= 288))
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 13 && y >= 180 && x <= 116 && y <= 221) || (x >= 21 && y >= 220 && x <= 40 && y <= 234) || (x >= 81 && y >= 218 && x <= 106 && y <= 237))
break
}
default:
Send, {Numpad4}
}
Return
$Numpad5::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 93 && y >= 48 && x <= 185 && y <= 168)
break
}
case "lhz_dun02":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 93 && y >= 87 && x <= 204 && y <= 204)
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 170 && y >= 122 && x <= 188 && y <= 161)
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 215 && y >= 126 && x <= 315 && y <= 252)
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 84 && y >= 118 && x <= 168 && y <= 223) || (x >= 159 && y >= 131 && x <= 192 && y <= 150) || (x >= 14 && y >= 195 && x <= 37 && y <= 234) || (x >= 37 && y >= 180 && x <= 106 && y <= 237) || (x >= 95 && y >= 180 && x <= 113 && y <= 226))
break
}
default:
Send, {Numpad5}
}
Return
$Numpad6::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 188 && y >= 91 && x <= 239 && y <= 191)
break
}
case "lhz_dun01":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 2 && y >= 143 && x <= 49 && y <= 193) || (x >= 259 && y >= 148 && x <= 298 && y <= 182) || (x >= 118 && y >= 2 && x <= 158 && y <= 36))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 190 && y >= 122 && x <= 221 && y <= 154)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 218 && y >= 187 && x <= 282 && y <= 211) || (x >= 270 && y >= 140 && x <= 371 && y <= 266) || (x >= 320 && y >= 266 && x <= 376 && y <= 287) || (x >= 300 && y >= 87 && x <= 380 && y <= 169))
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 196 && y >= 161 && x <= 285 && y <= 233)
break
}
default:
Send, {Numpad6}
}
Return
$Numpad7::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 4 && y >= 201 && x <= 96 && y <= 236) || (x >= 42 && y >= 178 && x <= 71 && y <= 214))
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 19 && y >= 172 && x <= 70 && y <= 234)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 24 && y >= 265 && x <= 150 && y <= 375)
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 67 && y >= 250 && x <= 215 && y <= 371)
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 12 && y >= 238 && x <= 119 && y <= 295) || (x >= 50 && y >= 220 && x <= 90 && y <= 245) || (x >= 108 && y >= 227 && x <= 125 && y <= 244))
break
}
default:
Send, {Numpad7}
}
Return
$Numpad8::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 74 && y >= 179 && x <= 178 && y <= 235)
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 109 && y >= 216 && x <= 172 && y <= 269)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 173 && y >= 220 && x <= 207 && y <= 289) || (x >= 126 && y >= 279 && x <= 262 && y <= 378) || (x >= 254 && y >= 311 && x <= 278 && y <= 367))
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 155 && y >= 250 && x <= 214 && y <= 279) || (x >= 160 && y >= 277 && x <= 289 && y <= 371))
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 66 && y >= 237 && x <= 166 && y <= 295) || (x >= 114 && y >= 221 && x <= 163 && y <= 244) || (x >= 108 && y >= 226 && x <= 122 && y <= 252) || (x >= 170 && y >= 256 && x <= 235 && y <= 286) || (x >= 198 && y >= 247 && x <= 207 && y <= 261))
break
}
default:
Send, {Numpad8}
}
Return
$Numpad9::
ExitLoop := 0
map := Memory("map")
switch map
{
case "abbey03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 161 && y >= 179 && x <= 235 && y <= 235)
break
}
case "lhz_dun03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 209 && y >= 170 && x <= 259 && y <= 233)
break
}
case "moc_fild22":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 208 && y >= 208 && x <= 271 && y <= 271) || (x >= 253 && y >= 265 && x <= 377 && y <= 371) || (x >= 240 && y >= 302 && x <= 262 && y <= 368))
break
}
case "odin_tem03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if(x >= 246 && y >= 215 && x <= 354 && y <= 374)
break
}
case "thor_v03":
While (!ExitLoop)
{
x := Memory("x")
y := Memory("y")
temp_x := x
temp_y := y
Press(DDLFly)
Sleep(1)
Loop,
{
x := Memory("x")
y := Memory("y")
if (x = temp_x) && (y = temp_y)
Sleep(1)
else
break
}
if((x >= 217 && y >= 163 && x <= 297 && y <= 297) || (x >= 165 && y >= 164 && x <= 220 && y <= 247) || (x >= 168 && y >= 245 && x <= 189 && y <= 259))
break
}
default:
Send, {Numpad9}
}
Return
GuiClose:
ExitApp
ExitSub:
Gui, Submit, NoHide
For Key, Value in Settings
IniWrite, % %Value%, teleport_conf.ini, Settings, % Value
ExitApp
ButtonHotkey:
GoSub, ButtonStart
Return