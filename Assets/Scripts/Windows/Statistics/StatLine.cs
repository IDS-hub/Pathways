using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI.Extensions;

public class StatLine : MonoBehaviour {

	[SerializeField] UIPolygon polygon;

	public void SetPoint(Vector2 start, Vector2 end){
		polygon.SetPoint(start, end);
	}

	public void SetAngle(float angle){
		polygon.SetAngle(angle);
	}
}
