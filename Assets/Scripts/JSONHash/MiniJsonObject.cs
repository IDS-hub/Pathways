using UnityEngine;
using System.Collections;

public class MiniJsonObject {

	Hashtable hash;

	public MiniJsonObject(string str){
		if (str.Length == 0)
			hash = new Hashtable();
		else
			hash = str.hashtableFromJson ();
	}

	public MiniJsonObject(Hashtable table){
		hash = table;
		if(table == null)
			hash = new Hashtable();
	}

	public MiniJsonObject(){
		hash = new Hashtable();
	}

	public Hashtable GetHashTable{
		get{
			return hash;
		}
	}

	public string GetField(string key, string fallback){
		if (hash != null && hash.ContainsKey (key)) {
			return hash [key].ToString ();
		}
		return fallback;
	}

	public int GetField(string key, int fallback){
		if (hash != null && hash.ContainsKey (key)) {
			float number = float.Parse(hash [key].ToString ());
			return (int)number;
		}
		return fallback;
	}

	/* public void SetField(string key, int value){
		if (hash != null && hash.ContainsKey (key)) {
			hash [key] = value;
		}
	} */

	public float GetField(string key, float fallback){
		if (hash != null && hash.ContainsKey (key)) {
			return float.Parse(hash [key].ToString ());
		}
		return fallback;
	}

	public long GetField(string key, long fallback){
		if (hash.ContainsKey (key)) {
			return long.Parse(hash [key].ToString ());
		}
		return fallback;
	}

	public bool GetField(string key, bool fallback){
		if (hash != null && hash.ContainsKey (key)) {
			return (bool)hash [key];
		}
		return fallback;
	}

	public MiniJsonObject GetJsonObject(string key, bool needFallback = true){
		if (hash != null && hash.ContainsKey (key))
			return new MiniJsonObject (hash [key] as Hashtable);
		//else
		return needFallback ? new MiniJsonObject("") : null;
	}

	public MiniJsonArray GetJsonArray(string key, bool needFallback = true){
		if (hash != null && hash.ContainsKey (key) && hash [key] != null)
			return new MiniJsonArray (hash [key] as ArrayList);
		//else
		return needFallback ? new MiniJsonArray ("") : null;
	}

	public ArrayList GetArrayList(string key, bool needFallback = true){
		if (hash != null && hash.ContainsKey (key))
			return (hash [key] as ArrayList);
		//else
		return needFallback ? new ArrayList () : null;
	}

    public override string ToString(){
		return MiniJSON.jsonEncode (hash);
	}

	public void AddField(string key, ArrayList value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, string value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, bool value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, int value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, float value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, long value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}

	public void AddField(string key, Hashtable value){
		if (hash.ContainsKey (key))
			hash [key] = value;
		else
			hash.Add (key, value);
	}
}
