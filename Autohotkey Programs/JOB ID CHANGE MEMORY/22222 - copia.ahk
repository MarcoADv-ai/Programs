#NoEnv
#UseHook
#SingleInstance Off
#NoTrayIcon
#MaxHotkeysPerInterval 999999999999999
SetDefaultMouseSpeed, 0
SetTitleMatchMode, 2
SendMode, Input
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
Hp_client := "0x01138DDC"
MaxHP_client := "0x01138DE0"
Sp_client := "0x01138DE4"
MaxSP_client := "0x01138DE8"
Status_client := "0x010DD284"
Char_client := "0x0113B7B0"
map_client := "0x00E43F48"
jobid_client := "0x0113521C"
x_client := "0x00E34D0C"
y_client := "0x00E34D10"
sight_client := "0x01013E64"
classchange_client := "0x0019C524"
VersionList := ["client"]
IDList := [272, 304, 328, 64, 312, 344, 320]
Memory(Value, HWND_F := 0, Version_F := 0, Size := 23, Temp := "")
{
Global
Str := (Value = "Map" || Value = "Char") ? "String" : ""
HWND_F := HWND_F ? HWND_F : HWND
Version_F := Version_F ? Version_F : Version
If (Value = "Buffs")
{
CHAR_BUFFS := []
For Key, Address in Buffs_%Version_F%
{
I_BUFF := Memory_Read(HWND_F, Address)
If (I_BUFF > 1000)
Break
CHAR_BUFFS[A_Index] := I_BUFF
}
Return CHAR_BUFFS
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
}else  {
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
V_PID := 0, A_Char := 0
pIndex := 1
WinGet, WinList, List
GuiControl,, DDLClient, |
Loop, %WinList%
{
WinGet, PID0, PID, % "ahk_id" WinList%A_Index%
WinGetTitle, wTitle, ahk_pid %PID0%
If (InStr(wTitle, "HocusPocusKNGGGG"))
Continue
HWND0 := Memory_GetProcessHandle(PID0)
ClientID := Memory(0x0040003C, HWND0)
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
Element%pIndex% := pName " [ " Info " ]"
GuiControl,, DDLClient, % Element%pIndex%
If (FailCheck && Char = V_Char)
{
GuiControl, ChooseString, DDLClient, % Element%pIndex%
PID := PID0
HWND := Memory_GetProcessHandle(PID0)
FailCheck := 2
SB_HP := Memory("MaxHP"), SB_SP := Memory("MaxSP")
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
getProcessBaseAddress(WindowTitle, windowMatchMode := "3")
{
if (windowMatchMode && A_TitleMatchMode != windowMatchMode)
{
mode := A_TitleMatchMode
StringReplace, windowMatchMode, windowMatchMode, 0x
SetTitleMatchMode, %windowMatchMode%
}
WinGet, hWnd, ID, %WindowTitle%
if mode
SetTitleMatchMode, %mode%
if !hWnd
return
return DllCall(A_PtrSize = 4
? "GetWindowLong"
: "GetWindowLongPtr"
, "Ptr", hWnd, "Int", -6, A_Is64bitOS ? "Int64" : "UInt")
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
LoadSettings(Section := "Settings")
{
Global
For Key, Value in Settings
{
IniRead, %Value%, hocus_cfg.ini, % Section, %Value%, %A_Space%
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
}
WriteMemory(address, value, pid) {
    ; Asegúrate de que la biblioteca de acceso a memoria esté incluida.
    hProcess := DllCall("OpenProcess", "uint", 0x1F0FFF, "uint", 0, "uint", pid, "uint")
    if (hProcess) {
        VarSetCapacity(buffer, 4) ; Ajusta el tamaño según el tipo de datos que estés escribiendo
        NumPut(value, buffer) ; Coloca el valor en el buffer
        result := DllCall("WriteProcessMemory", "uint", hProcess, "uint", address, "uint", &buffer, "uint", 4, "uint", 0) ; Cambia 4 al tamaño real de tu dato
        DllCall("CloseHandle", "uint", hProcess)
        return result
    }
    return 0
}
OnExit, ExitSub
Dir = %A_AppData%\uwuu
IniRead, DllMode, hocus_cfg.ini, Settings, DllMode, 0
IfNotExist, %Dir%
    FileCreateDir, %Dir%
IfNotExist, hocus_cfg.ini
    FileAppend, [Settings], hocus_cfg.ini
FileInstall, uwuu\sonido.wav, %Dir%\sonido.wav, 1

Gui, Font, s8, Segoe UI

; Grupo para el cliente
Gui, Add, GroupBox, xm ym w230 h53, Client
Gui, Add, DDL, xp+15 yp+20 r2 w200 vDDLClient +AltSubmit,
ClientList()
WM_COMMAND := 0x0111, OnMessage(WM_COMMAND, "On_CBN_DROPDOWN")

; Grupo para Skills
Gui, Add, GroupBox, xm ym+60 w230 h140, Skills

; Skill 1
Gui, Add, Text, x20 y90, Skill 1:
Gui, Add, Edit, x70 y87 w50 vSkill1, ; Skill ID

; Botón de inyección
Gui, Add, Button, xm+15 ym+230 w200 h30 vButtonInject, Inject

;Start Button
Gui, Add, Button, xm+15 ym+250 w200 h30 vButtonStart, Start

Settings := ["Skill1_ID", "Skill1_Lvl", "Skill2_ID", "Skill2_Lvl", "Skill3_ID", "Skill3_Lvl"]

LoadSettings()
Gui, Font, Bold
Gui, Add, StatusBar
SB_SetText("Inject program to continue...")
Gui, Show,, HocusPocus
GuiControl, Hide, FriendList
GuiControl, Hide, ButtonStart
Return

ButtonInject:
Gui, Submit, NoHide
GuiControlGet, Value,, DDLClient, Text
If !Value
Return
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
BaseAddress := getProcessBaseAddress(ahk_pid %PID%)
pointer0 :=  BaseAddress + 0x0019C524
address0 := ReadMemory(pointer0, PID)
pointer1 := address0
Loop, 4
{
SB_SetText("Injecting.")
Sleep(25)
SB_SetText("Injecting..")
Sleep(25)
SB_SetText("Injecting...")
Sleep(25)
SB_SetText("Injecting....")
Sleep(25)
SB_SetText("Injecting...")
Sleep(25)
SB_SetText("Injecting..")
Sleep(25)
SB_SetText("Injecting.")
Sleep(25)
}
SB_SetText("Press F12 to Start/Stop")
GuiControl, Disable, DDLClient
GuiControl, Show, FriendList
GuiControl, Hide, ButtonInject
GuiControl, Show, ButtonStart
Hotkey, F12, ButtonStart
Return

ButtonStart:
Gui, Submit, NoHide
GuiControlGet, Value,, ButtonStart
If (Value = "Start")
{
    GuiControl,, ButtonStart, Stop
    GuiControl, Disable, DDLHocus
    GuiControl, Disable, DDLCancel
    ExitLoop := 0  ; Reiniciar la variable para el bucle
    SetTimer, ButtonMain, On  ; Iniciar el bucle principal
}
Else
{
    GuiControl,, ButtonStart, Start
    GuiControl, Enable, DDLHocus
    GuiControl, Enable, DDLCancel
    ExitLoop := 1  ; Pausar el bucle
}
Return

ButtonMain:
SetTimer, ButtonMain, Off
While (!ExitLoop)
{
    SB_SetText("Press F12 to Start/Stop")
    Sleep(50)
    WriteMemory(jobid_client, Skill1, PID)
    Sleep(200)
}
Return
GuiClose:
ExitApp
ExitSub:
Gui, Submit, NoHide
For Key, Value in Settings
IniWrite, % %Value%, hocus_cfg.ini, Settings, % Value
ExitApp
ButtonHotkey:
GoSub, ButtonStart
Return
