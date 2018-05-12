using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using DG.Tweening;

public class DrawLine2D : MonoBehaviour {

	float ANIM_TIME = 2f;

	public float lineWidth;

	public List<RectTransform> Lines = new List<RectTransform>();
	public List<LRPoint> Points = new List<LRPoint>();

	[SerializeField] Transform linePrefab;
	[SerializeField] GameObject pointPrefab;
	[SerializeField] GameObject datePrefab;

	private List<GameObject> graphicPoints = new List<GameObject>();
	private List<Material> mats = new List<Material>();
	private List<GameObject> dates = new List<GameObject>();

	private LRPoint prevPoint;
	private LRPoint nextPoint;

	private int queuePoint;

	public void SetLinePoints(List<LRPoint> points) {
		if (points.Count > 0) {
			ANIM_TIME = ANIM_TIME / points.Count;
			Points = points;

			CreateDateArray(Points);
			CreateGraphicPoints(Points);
			FillGraph();
		}
	}

	// printing the dates on graph
	private void CreateDateArray(List <LRPoint> points) {
		if (dates == null)
			dates = new List<GameObject>();

		for (int i = 0; i < points.Count; i++) { // tamal 1 to 0
			var date = Instantiate(datePrefab, transform);
			date.GetComponent<RectTransform>().localPosition = new Vector3(points[i].PointPosition.x + 20f, 15f);
			date.GetComponent<UnityEngine.UI.Text>().text = points[i].Date;
			dates.Add(date);
		}
	}

	// showing graph points
	private void CreateGraphicPoints(List <LRPoint> points) {
		if (graphicPoints == null)
			graphicPoints = new List<GameObject>();

		for (int i = 0; i < points.Count; i++) { // tamal 1 to 0
			var point = Instantiate(pointPrefab, transform);
			point.GetComponent<RectTransform>().localPosition = points[i].PointPosition;
			point.GetComponent<Image>().color = Color.blue;
			graphicPoints.Add(point);
		}
	}

	// fill graph points with line
	private void FillGraph() {
		if (Points.Count > 1) { // tamal
			queuePoint = 1;

			prevPoint = Points[0];
			nextPoint = Points[queuePoint];

			SetLine(prevPoint.PointPosition, nextPoint.PointPosition, prevPoint.PointColor, nextPoint.PointColor);
		}
	}

	// drawing line with animation
	private void SetLine(Vector2 startPoint, Vector2 endPoint, Color startColor, Color endColor) {
		if (mats == null)
			mats = new List<Material>();

		Vector3 differenceVector = endPoint - startPoint;

		RectTransform imageRectTransform = (RectTransform)Instantiate(linePrefab, transform);

		Lines.Add(imageRectTransform);

		imageRectTransform.DOSizeDelta(new Vector2(differenceVector.magnitude, lineWidth), ANIM_TIME).SetEase(Ease.Linear).OnComplete(OnCompleteTween);
		imageRectTransform.pivot = new Vector2(0, 0.5f);
		imageRectTransform.localPosition = startPoint;
		float angle = Mathf.Atan2(differenceVector.y, differenceVector.x) * Mathf.Rad2Deg;
		imageRectTransform.rotation = Quaternion.Euler(0, 0, angle);

		//imageRectTransform.GetComponent<StatLine>().SetAngle(90 - angle);
	}

	void OnCompleteTween() {
		queuePoint++;

		prevPoint = nextPoint;
		// on set of line draw complete, now draw next set
		if (queuePoint < Points.Count) {
			nextPoint = Points[queuePoint];
			SetLine(prevPoint.PointPosition, nextPoint.PointPosition, prevPoint.PointColor, nextPoint.PointColor);
		}
	}

	public void ResetStatistics() {
		DeletePoints();
		DeleteMaterials();
		DeleteDates();
	}

	void DeletePoints() {
		if (graphicPoints == null)
			return;
        
		for (int i = 0; i < graphicPoints.Count; i++) {
			Destroy(graphicPoints[i]);
		}
		graphicPoints.TrimExcess();
		graphicPoints = null;
	}

	void DeleteMaterials() {
		if (mats == null)
			return;

		for (int i = 0; i < mats.Count; i++) {
			Destroy(mats[i]);
		}
		mats.TrimExcess();
		mats = null;
	}

	void DeleteDates() {
		if (dates == null)
			return;

		for (int i = 0; i < dates.Count; i++) {
			Destroy(dates[i]);
		}
		dates.TrimExcess();
		dates = null;
	}
}
