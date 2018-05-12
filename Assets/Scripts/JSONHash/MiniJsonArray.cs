using UnityEngine;
using System.Collections;
using System.Collections.Generic;

public class MiniJsonArray {

	ArrayList jsonArray;
	MiniJsonObject child = null;

	public MiniJsonArray(string str){
		jsonArray = str.arrayListFromJson (); 
	}

	public MiniJsonArray(ArrayList list){
		jsonArray = list; 
	}

	public int Count{
		get{ 
			if (jsonArray != null)
				return jsonArray.Count;
			else
				return 0;
		}
	}

	public void Clear(){
		if (jsonArray.Count > 0)
			jsonArray.Clear ();
	}

	public MiniJsonObject Get(int i){
		child = new MiniJsonObject(jsonArray [i] as Hashtable);
		return child;
	}

	public string GetString(int i){
		return jsonArray [i] as string;
	}

	public void Set(Hashtable obj){
		jsonArray.Add (obj);
	}

	public void DoSort(IComparer c){
		jsonArray.Sort (c);
	}

	public void Set(MiniJsonObject obj){
		jsonArray.Add (obj.GetHashTable);
	}

	public void Set(MiniJsonObject obj, int index){
		jsonArray [index] = obj.GetHashTable;
	}

	public void Remove(int index){
		jsonArray.RemoveAt (index);
	}

	public ArrayList GetArrayList(){
		return jsonArray;
	}

    public override string ToString(){
		return MiniJSON.jsonEncode (jsonArray);
	}
}
