<?PHP
/* ---------------------- */
/* ZIP Fallback for Linux */
/* ---------------------- */

if ( !extension_loaded('zip') )
{
	function ShellFix($s)
	{
	  return "'".str_replace("'", "'\''", $s)."'";
	}

	function zip_open($s)
	{
	  $fp = @fopen($s, 'rb');
	  if(!$fp) return false;
	  
	  $lines = Array();
	  $cmd = 'unzip -v '.shellfix($s);
	  exec($cmd, $lines);
	  
	  $contents = Array();
	  $ok=false;
	  foreach($lines as $line)  
	  {
		if($line[0]=='-') { $ok=!$ok; continue; }
		if(!$ok) continue;
		
		$length = (int)$line;
		$fn = trim(substr($line,58));
		
		$contents[] = Array('name' => $fn, 'length' => $length);
	  }
	  
	  return
		Array('fp'       => $fp,  
			  'name'     => $s,
			  'contents' => $contents,
			  'pointer'  => -1);
	}
							  
	function zip_read(&$fp)
	{
	  if(!$fp) return false; 
	  
	  $next = $fp['pointer'] + 1;
	  if($next >= count($fp['contents'])) return false;

	  $fp['pointer'] = $next;
	  return $fp['contents'][$next];
	}

	function zip_entry_name(&$res)
	{
	  if(!$res) return false;
	  return $res['name'];
	}
							
	function zip_entry_filesize(&$res)
	{
	  if(!$res) return false;
	  return $res['length'];
	}

	function zip_entry_open(&$fp, &$res) // NOT USED
	{
	  if(!$res) return false;

	  $cmd = 'unzip -p '.shellfix($fp['name']).' '.shellfix($res['name']);
	  
	  $res['fp'] = popen($cmd, 'r');
	  return !!$res['fp'];   
	}

	function zip_entry_read(&$res, $nbytes)
	{
	  return fread($res['fp'], $nbytes);
	}

	function zip_entry_close(&$res) // NOT USED
	{
	  fclose($res['fp']);
	  unset($res['fp']);
	}

	function zip_close(&$fp)
	{
	  fclose($fp['fp']);
	}
}
