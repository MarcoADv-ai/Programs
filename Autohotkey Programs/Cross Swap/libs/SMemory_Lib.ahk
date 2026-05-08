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
Char_2018a := "0x010DF5D8"
Map_2018a := "0x010D856C"
Job_2018a := "0x010D93D8"
Status_2018a := "0x010D8CF0"
Weapon_2018a := "0x010D96A0"
Shield_2018a := "0x010D9A20"
Armor_2018a := "0x010D9940"
CoordX_2018a := "0x010D8CF8"
CoordY_2018a := "0x010D8CFC"

VersionList := ["2018a"]
Memory(Value, HWND_F := 0, Version_F := 0, Size := 23, Temp := "")
{
	Global
	Str := (Value = "Map" || Value = "Char" || Value = "Weapon" || Value = "Shield" || Value = "Armor") ? "String" : ""
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
Memory_WriteByte(process_handle, address, value)
{
	DllCall("VirtualProtectEx", "UInt", process_handle, "UInt", address, "UInt", 1, "UInt", 0x04, "UInt *", 0)
	DllCall("WriteProcessMemory", "UInt", process_handle, "UInt", address, "UChar *", value, "UInt", 1, "UInt *", 0)
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
		If (InStr(wTitle, "SMemory") || InStr(wTitle, "vBeta"))
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

getProcessBaseAddress(WindowTitle, windowMatchMode := "3")    ;WindowTitle can be anything ahk_exe ahk_class etc
{
    if (windowMatchMode && A_TitleMatchMode != windowMatchMode)
    {
        mode := A_TitleMatchMode ; This is a string and will not contain the 0x prefix
        StringReplace, windowMatchMode, windowMatchMode, 0x ; remove hex prefix as SetTitleMatchMode will throw a run time error. This will occur if integer mode is set to hex and matchmode param is passed as an number not a string.
        SetTitleMatchMode, %windowMatchMode%    ;mode 3 is an exact match
    }
    WinGet, hWnd, ID, %WindowTitle%
    if mode
        SetTitleMatchMode, %mode%    ; In case executed in autoexec
    if !hWnd
        return ; return blank failed to find window
    return DllCall(A_PtrSize = 4     ; If DLL call fails, returned value will = 0
        ? "GetWindowLong"
        : "GetWindowLongPtr"
        , "Ptr", hWnd, "Int", -6, A_Is64bitOS ? "Int64" : "UInt")  
        ; For the returned value when the OS is 64 bit use Int64 to prevent negative overflow when AHK is 32 bit and target process is 64bit 
        ; however if the OS is 32 bit, must use UInt, otherwise the number will be huge (however it will still work as the lower 4 bytes are correct)      
        ; Note - it's the OS bitness which matters here, not the scripts/AHKs
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
